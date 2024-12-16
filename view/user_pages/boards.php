<?php
// Include the database configuration
include '../../db/config.php';

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../logIn.php");
    exit();
}

// Fetch user details from the database
$user_id = $_SESSION['user_id'];

$query = "SELECT fname, lname, email FROM Users WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

// Fetch boards for the current user
$boards_query = "SELECT * FROM Boards WHERE user_id = ?";
$boards_stmt = $conn->prepare($boards_query);
$boards_stmt->bind_param("i", $user_id);
$boards_stmt->execute();
$boards_result = $boards_stmt->get_result();

// Close the statement and connection
$stmt->close();
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vividly | Account Settings</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../../assets/css/boards.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h1 class="logo"><a href="landingpage.php">Vividly</a></h1>
            </div>

            <nav class="sidebar-nav">
                <a href="account.php" class="nav-item">
                    <i class="fas fa-user"></i>
                    <span>Profile</span>
                </a>
                <a id="boards" href="boards.php" class="nav-item">
                    <i class="fas fa-th-large"></i>
                    <span>Boards</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="landingpage.php" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    <span>Back</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <h1 id="welcome">Welcome <?= htmlspecialchars($user['fname']) ?> !</h1>
            <a href="boards_display.php">
                <p id="welcome-text">Click me</p>
            </a>
            <p id="welcome-text">Here are your boards</p>

            <!-- Create Board button -->
            <div class="absolute top-20 right-40 m-4">
                <input class="peer hidden" type="checkbox" id="toggle" />
                <label
                    class="absolute z-10 flex size-[3.2rem] cursor-pointer items-center justify-center rounded-full border bg-black duration-500 peer-checked:rotate-45 peer-checked:bg-red-500"
                    for="toggle" onclick="openBoardModal()">
                    <svg class="fill-white" viewBox="0 0 0.6 0.6" height="20" width="20">
                        <path
                            d="M.325.275H.55v.05H.325V.55h-.05V.325H.05v-.05h.225V.05h.05z"
                            fill-rule="evenodd"></path>
                    </svg>
                </label>
            </div>

            <!-- Board Creation Modal -->
            <div id="board-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900 bg-opacity-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full relative z-[101]">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Create New Board</h2>
                    <form id="board-form" method="POST" action="../../db/user_db/create_board.php">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="board-title">
                                Board Title
                            </label>
                            <input type="text" id="board-title" name="title" required
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="board-description">
                                Description
                            </label>
                            <textarea id="board-description" name="description"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                rows="3"></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button type="button" onclick="closeModal()"
                                class="mr-2 px-4 py-2 text-gray-600 border rounded hover:bg-gray-100">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Create</button>
                        </div>
                    </form>
                </div>
            </div>




            <!-- Boards -->
            <?php while ($board = $boards_result->fetch_assoc()): ?>
                <div class="relative flex flex-col my-10 mx-[9.5rem] shadow-sm w-64">
                    <div class="relative overflow-hidden">
                        <?php if (!empty($board['cover_image'])): ?>
                            <img
                                src="<?= htmlspecialchars($board['cover_image']) ?>"
                                alt="card-image"
                                class="w-full h-auto object-cover rounded-3xl" />
                        <?php else: ?>
                            <div class="w-full h-48 bg-black rounded-3xl"></div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-2">
                            <h6 class="text-white text-lg font-semibold italic">
                                <?= htmlspecialchars($board['title']) ?>
                            </h6>
                            <p class="text-slate-500 text-sm leading-normal font-light">
                                <?= htmlspecialchars($board['description']) ?>
                            </p>
                        </div>
                        <p class="text-slate-500 text-sm leading-normal font-light">
                            <?php
                            // Count pins in this board
                            $pin_query = "SELECT COUNT(*) as pin_count FROM Pins WHERE board_id = ?";
                            $pin_stmt = $conn->prepare($pin_query);
                            $pin_stmt->bind_param("i", $board['board_id']);
                            $pin_stmt->execute();
                            $pin_count = $pin_stmt->get_result()->fetch_assoc()['pin_count'];
                            $pin_stmt->close();
                            ?>
                            <?= $pin_count ?> pins
                        </p>
                    </div>
                </div>
            <?php endwhile; ?>

            <div class="relative flex flex-col my-10 mx-[9.5rem] shadow-sm w-64">
                <div class="relative overflow-hidden">
                    <img 
                        src="https://images.unsplash.com/photo-1499696010180-025ef6e1a8f9?ixlib=rb-4.0.3&amp;ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&amp;auto=format&amp;fit=crop&amp;w=1470&amp;q=80" 
                        alt="card-image"
                        class="w-full h-auto object-cover rounded-3xl"
                    />
                </div>
                <div class="p-4">
                    <div class="flex items-center mb-2">
                        <h6 class="text-white text-lg font-semibold italic">
                            Wooden House, Florida
                        </h6>
                    </div>
                    <p class="text-slate-500 text-sm leading-normal font-light">
                        160 pins
                    </p>
                </div>
            </div>


        </main>
    </div>
    <script src="../../functions/user_js/board_creation_modal.js"></script>
</body>

</html>