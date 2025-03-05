<?php

include './config/database.php';  //add database connection
?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Microsoft To-Do Clone</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script> <!-- Icon Library -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <style>
        body {
            background-color: #0B192C;
            color: #333;
        }

        /* width */
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
</head>

<body class="h-screen flex flex-col md:flex-row">
    <!-- Mobile/Tablet Toggle Button -->
    <button id="menu-toggle" class="lg:hidden p-3 text-white bg-black fixed top-4 left-4 rounded-md z-50">☰</button>

    <!--left Sidebar -->
    <aside id="sidebar"
        class="w-80 overflow-y-scroll bg-white p-5 flex flex-col fixed lg:relative inset-y-0 left-0 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 h-full shadow-lg">

        <!-- Sidebar Header with Close Button -->
        <div class="flex justify-between items-center mb-5">
            <div class="flex items-center space-x-2">
                <div
                    class="w-10 h-10 bg-[#1E3E62] rounded-full flex items-center  justify-center text-xl font-bold text-white">
                    <?php
                    if (isset($_SESSION['logindata'])) {
                        echo $_SESSION['logindata']['firstname'][0] . $_SESSION['logindata']['lastname'][0];
                    }
                    ?>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-black">
                        <?php echo isset($_SESSION['logindata']) ? $_SESSION['logindata']['firstname'] : 'Guest'; ?>
                    </h2>
                    <p class="text-sm text-gray-600">
                        <?php echo isset($_SESSION['logindata']) ? $_SESSION['logindata']['email'] : 'No Email'; ?>
                    </p>

                </div>
            </div>
            <button id="close-sidebar" class="lg:hidden p-2 text-gray-700 bg-gray-200 rounded-md">✖</button>
        </div>

        <input type="text" placeholder="Search" class="w-full bg-gray-100 px-3 py-2 rounded-md text-gray-700 mb-5">

        <nav class="space-y-2">
            <a href="#" class="flex items-center p-2 bg-[#1E3E62] rounded-lg text-white"><span class="mr-2">🌞</span> My
                Day</a>
            <a href="#" class="flex items-center p-2 hover:bg-black rounded-lg text-gray-700 hover:text-white"><span
                    class="mr-2">⭐</span> Important</a>
            <a href="#" class="flex items-center p-2 hover:bg-black rounded-lg text-gray-700 hover:text-white"><span
                    class="mr-2">📅</span> Planned</a>
            <a href="#" class="flex items-center p-2 hover:bg-black rounded-lg text-gray-700 hover:text-white"><span
                    class="mr-2">📌</span> Assigned to me</a>
            <a href="#" class="flex items-center p-2 hover:bg-black rounded-lg text-gray-700 hover:text-white"><span
                    class="mr-2">📋</span> Tasks</a>
        </nav>

        <input type="hidden" class="activeinput">
        <ul id="newlistdata" class="mt-4"></ul>


        <!--list context menu-->

        <div id="contextMenu" class="hidden absolute bg-white shadow-md rounded-lg w-56 py-2 border border-gray-200">
            <ul class="text-sm text-gray-700">
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-pencil-simple w-4 h-4 mr-2"></i> Rename list <span
                        class="ml-auto text-gray-400">F2</span>
                </li>
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-user-plus w-4 h-4 mr-2"></i> Share list
                </li>
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-printer w-4 h-4 mr-2"></i> Print list
                </li>
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-envelope w-4 h-4 mr-2"></i> Email list
                </li>
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-push-pin w-4 h-4 mr-2"></i> Pin to Start
                </li>
                <li class="flex items-center px-4 py-2 hover:bg-gray-100 cursor-pointer">
                    <i class="ph ph-copy w-4 h-4 mr-2"></i> Duplicate list
                </li>
            </ul>
            <div class="border-t mt-1"></div>
            <ul>
                <li class="flex delete items-center px-4 py-2 text-red-600 hover:bg-red-100 cursor-pointer">
                    <i class="ph ph-trash w-4 h-4 mr-2"></i> Delete list
                </li>
            </ul>
        </div>



        <button id="addnewlistbtn"
            class="mt-auto px-3 py-2 bg-gray-100 rounded-md text-gray-700 hover:bg-black hover:text-white">+
            NewList</button>
    </aside>

    <!-- Main Content -->
    <main is_open='0' class="w-full main_screenspace  p-4 bg-[#0B192C] transition-all overflow-y-auto">
        <div class="flex">
            <div class="fixed top-4 right-0 flex items-center">

                <button id="logoutbtn" class="p-2 bg-black text-white rounded-md">
                    <a href="./config/logout.php">Logout</a>
                </button>

            </div>
        </div>


        <!-- Custom Right-Click Context Menu -->
        <div id="contextMenuForTask" class="hidden absolute bg-white border rounded-lg shadow-lg w-48">
            <ul class="py-1 text-gray-700">
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">✏️ Rename</li>
                <li class="openpopup menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">🗑️ Delete</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">✅ Mark as Done</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">➕ Add to My Day</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">⭐ Mark as Important</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">📅 Due Today</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer">📆 Due Tomorrow</li>
                <li class="menu-item px-4 py-2 hover:bg-gray-200 cursor-pointer" id="pickDate">📅 Pick a Due Date</li>
            </ul>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg w-80 text-center">
                <h3 class="text-lg font-bold mb-3">Confirm Deletion</h3>
                <p class="text-gray-600 mb-4">Are you sure you want to delete?</p>
                <input type="hidden" id="taskToDelete">
                <div class="flex justify-center space-x-4">
                    <button id="confirmDelete"
                        class="deletetask bg-red-500 text-white px-4 py-2 rounded">Delete</button>
                    <button id="cancelDelete" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>

        <div id="tasklist" class="mt-10 mx-auto ml-12">
            <!-- Task Items will be dynamically added here -->
        </div>

        <!-- Task Input Form -->
        <form class="fixed bottom-4 ml-20 w-full max-w-5xl mx-auto" action="./config/server.php" method="POST"
            id="taskform1">
            <input type="hidden" name="taskform" value="1">
            <input type="hidden" name="user_id" value="<?php echo $_SESSION['loginid']; ?>">
            <div class="relative">
                <input type="text" id="taskforminput" name="taskinput"
                    class="w-full py-3 pl-10 text-sm text-gray-700 border border-gray-300 rounded-full bg-gray-100 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="What's on your mind?" required />
                <button type="submit" id="addtask"
                    class="absolute bg-black text-white hover:bg-gray-800 font-medium rounded-full text-sm px-6 py-3 right-1 transition duration-300">
                    Add
                </button>
            </div>
        </form>

        <div class="sidebar h-screen w-80 fixed top-0 right-0 hidden ">
            <div class="bg-gray-800 text-white h-screen p-6 rounded-lg w-96 shadow-lg">
                <!-- Task Header -->
                <div class="flex items-center justify-between">
                    <input type="checkbox" id="checkbox" class="w-5 h-5 accent-blue-500">
                    <input type="text" value="Task Title"
                        class="bg-transparent text-lg font-semibold outline-none w-full ml-2">
                    <button class="text-gray-400 hover:text-yellow-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-6 h-6" viewBox="0 0 24 24">
                            <path
                                d="M12 17.75l-6.16 3.75 1.18-7.03-5.02-4.89 6.99-1.01L12 2l3.01 6.07 6.99 1.01-5.02 4.89 1.18 7.03z" />
                        </svg>
                    </button>
                </div>

                <!-- Add Step -->
                <button class="text-blue-400 hover:text-blue-300 mt-2 text-sm">+ Add step</button>
                <!-- Action Buttons -->

                <div class="mt-4 space-y-2">
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M12 3v10h9v3h-9v8H9v-8H3v-3h6V3h3z" />
                        </svg>
                        <span class="ml-2">Add to My Day</span>
                    </button>
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-red-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zm0 20c-4.962 0-9-4.038-9-9s4.038-9 9-9 9 4.038 9 9-4.038 9-9 9zm-.5-13h1v6h-1zm0 7h1v1h-1z" />
                        </svg>
                        <span class="ml-2">Remind me</span>
                    </button>
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-green-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M19 3h-2V1h-2v2H9V1H7v2H5C3.9 3 3 3.9 3 5v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V10h14v9z" />
                        </svg>
                        <span class="ml-2">Add due date</span>
                    </button>
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-blue-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M6 11h12v2H6zm0 4h12v2H6zm0-8h12v2H6z" />
                        </svg>
                        <span class="ml-2">Repeat</span>
                    </button>
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-purple-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M12 12c2.21 0 4-1.79 4-4S14.21 4 12 4s-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                        </svg>
                        <span class="ml-2">Assign to</span>
                    </button>
                    <button class="flex items-center w-full text-left p-2 hover:bg-gray-700 rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-yellow-400" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zM6 20V4h7v5h5v11H6z" />
                        </svg>
                        <span class="ml-2">Add file</span>
                    </button>
                </div>

                <!-- Notes Section -->
                <textarea class="bg-gray-700 w-full h-16 mt-4 p-2 rounded text-white placeholder-gray-400"
                    placeholder="Add note"></textarea>

                <!-- Footer -->
                <div class="text-gray-400 text-xs mt-4 flex justify-between">
                    <span>Created a few moments ago</span>
                    <button class="text-red-400 hover:text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM18 4h-2.5l-1-1h-5l-1 1H6v2h12V4z" />
                        </svg>
                    </button>
                </div>
            </div>

        </div>

        <!-- right click task button -->
        <div id="contextm" class="hidden"></div>
    </main>
    <!-- right Sidebar -->

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const menuToggle = document.getElementById("menu-toggle");
            const closeSidebar = document.getElementById("close-sidebar");
            const sidebar = document.getElementById("sidebar");

            menuToggle.addEventListener("click", () => {
                sidebar.classList.toggle("-translate-x-full");
            });

            closeSidebar.addEventListener("click", () => {
                sidebar.classList.add("-translate-x-full");
            });

            fetchTasks();
            fetchlist();
            $("#addtask").on("click", function (e) {
                e.preventDefault();
                let form = $("#taskform1");
                let url = form.attr("action");

                $.ajax({
                    url: url,
                    type: "POST",
                    data: form.serialize(),
                    contentType: "application/x-www-form-urlencoded; charset=UTF-8",
                    success: function (response) {
                        const arr = JSON.parse(response);
                        if (arr.success) {
                            $("#taskforminput").val("");
                            fetchTasks();
                        } else {
                            console.log("Data not inserte");
                        }
                    },
                    error: function (response) {
                        console.log(response);
                    }
                });
            });
        });

        function fetchTasks() {
            $.ajax({
                url: "./config/server.php",
                type: "POST",
                data: { getdata: true },
                success: function (response) {
                    const arr = JSON.parse(response);
                    if (arr.success) {
                        renderdata(arr.tasklist);
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            });
        }
        // render task data
        function renderdata(data) {
            const tasklistdata = $("#tasklist").empty();
            data.forEach(task => {
                tasklistdata.append(`<div class="flex justify-between taskitembtn rightclickmenu bg-[#1E3E62] py-2 mr-4 px-4 rounded-lg shadow mb-1" id="${task.id}"><span id="tasksidebar"class="text-lg text-white font-semibold mb-1 ml-4">${task.task}</span>
                             <div class="space-x-2">
                                <button class="edit-btn text-yellow-500 hover:text-yellow-600 ">
                                    <i class="ph ph-pencil-simple text-xl"></i>
                                </button>
                                <button class="delete-btn text-white hover:text-gray-400 ">
                                    <i class="ph ph-trash text-xl"></i>
                                </button>
                            </div>
                        </div>`);
            });
        }

        function getMaxOrZero(arr) {
            return arr.length ? Math.max(...arr) : 0;
        };

        $(document).on('click', '#addnewlistbtn', function (e) {
            // e.preventDefault();
            let allLists = $(".listspan");
            let temp_list = "untitled list";
            let list = "untitled list";
            let list_no = 1;
            if (allLists.length > 0) {
                alert("list already exist");
                console.log(allLists);
                let arrTemp = [];
                allLists.each(function () {
                    let temp_list = $(this).attr("temp_list");
                    let list_no = $(this).attr("list_no");
                    console.log(temp_list);
                    if ((temp_list.slice(0, 13)) === "untitled list") {
                        arrTemp.push(list_no);
                    };
                });
                let maxNo = getMaxOrZero(arrTemp);
                // console.log(maxNo)
                list_no = parseInt(maxNo) + 1;
                // let temp=parseInt(maxNo)-1;
                list = 'Untitled List (' + maxNo + ')';
                temp_list = 'Untitled List(' + maxNo + ')';

            }
            $.ajax({
                url: "./config/server.php",
                type: 'post',
                data: {
                    addList: true,
                    listname: list,
                    listno: list_no,
                    temp_list: temp_list
                },
                success: function (response) {
                    const res = JSON.parse(response);
                    console.log(res);
                    if (res.success) {

                        renderlist(res.data, 1);

                    }
                },
                error: function (response) {
                    console.log(response);
                },
            })
        })

        function fetchlist() {

            $.ajax({
                url: "./config/server.php",
                type: 'post',
                data: { getnewlist: 1 },
                success: function (response) {
                    const res = JSON.parse(response);
                    console.log(res);
                    if (res.success) {
                        renderlist(res.data);
                    }
                },
                error: function (response) {
                    console.log(response);
                }
            })
        }

        // function renderlist_1(data) {
        //     data.forEach(list => {
        //         let html = `<li class="flex listcontextmenu list-none py-1 mr-4 px-4 rounded-lg mb-1" data-id="${list.id}">

        //         <i class="fa-solid fa-bars mt-4 mr-2"></i>
        //         <span class=" listspan${list.id} listSpan text-lg font-semibold mt-2 ml-4">${list.newlist}(${list.id})</span>
        //         <input class="hidden listInput${list.id} listInput listInputActive bg-gray-50 border-none border-b-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-blue-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="list_name" value="${list.newlist}" />

        //         </li>`;
        //         $("#newlistdata").append(html);
        //     });
        // }
        //render newlist
        function renderlist(data, listinput = "") {
            // console.log(data);
            // const tasklistdata = ;
            // const tasklistdata = $("#tasklist").empty();
            //  html="";
            if (listinput == 1) {
                var span = "hidden";
                var input = "";
            } else {
                var span = "";
                var input = "hidden";
            }


            data.forEach(list => {
                let html = `<li class="flex listcontextmenu list-none py-1 mr-4 px-4 rounded-lg mb-1" data-id="${list.id}">
                
                <i class="fa-solid fa-bars mt-4 mr-2"></i>
                <span class="${span} listspan${list.id} listSpan text-lg font-semibold mt-2 ml-4 temp_list="${list.temp_list}" list_no="${list.list_no}">${list.newlist}</span>
                <input class="${input} listInput${list.id} listInput listInputActive bg-gray-50 border-none border-b-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-blue-900 dark:border-gray-600 dark:placeholder-gray-400 dark:text-black dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" name="list_name" value="${list.newlist}(${list.id})" />
                
                </li>`;


                $("#newlistdata").append(html);
                $(".listInput" + list.id).focus().select();
                $(".activeinput").attr("data-id", list.id);
            });
        };


        $(document).click(function (e) {
            if (!$(event.target).closest('.listInputActive').length) {

                let activeinput = $(".activeinput").attr("data-id");
                $('.listInput').addClass('hidden');
                $('.listSpan').removeClass('hidden');
                $(".listInput" + activeinput).focus().select();

            }
        });

        $(document).on('click', '.taskitembtn', function (e) {
            // e.preventDefault();
            $('.sidebar').toggle();
            let is_open = $(".main_screenspace").attr("is_open");
            if (is_open === '0') {

                $("#taskform1").removeClass("max-w-5xl");
                $("#taskform1").addClass("max-w-3xl");
                $('.main_screenspace').addClass('w-3/5');
                $('.main_screenspace').removeClass('w-full');
                $('.main_screenspace').attr('is_open', '1');
            } else {
                $("#taskform1").removeClass("max-w-3xl");
                $("#taskform1").addClass("max-w-5xl");
                $('.main_screenspace').addClass('w-full');
                $('.main_screenspace').removeClass('w-3/5');
                $('.main_screenspace').attr('is_open', '0');
            }
        });

        //context right click menu on task
        $(document).on("contextmenu", ".rightclickmenu", function (e) {
            e.preventDefault();
            console.log("right click");
            let posX = e.pageX;
            let posY = e.pageY;

            $("#contextMenuForTask").css({ "top": posY + "px", "left": posX + "px" }).removeClass("hidden");
            $(document).on("click", function () {
                $("#contextMenuForTask").addClass("hidden");
            });
            let id = $(this).attr("id");
            $(".deletetask").attr("data-id", id);

        });


        $(document).on("click", ".deletetask", function (e) {
            e.preventDefault();
            let id = $(this).attr("data-id");

            $.ajax({
                url: "./config/server.php",
                type: "POST",
                data: {
                    id: id,
                    deletetask: true // Ensure this key matches what PHP checks for
                },
                success: function (response) {
                    try {
                        let res = JSON.parse(response);
                        if (res.success) {

                            location.reload(); // Refresh the page to reflect changes
                        } else {
                            alert("Failed to delete task.");
                        }
                    } catch (error) {
                        console.error("Invalid JSON response:", response);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                }
            });
        });

        $(".openpopup").on("click", function () {
            $("#deleteModal").removeClass("hidden");
        });



        // Cancel delete
        $("#cancelDelete").on("click", function () {
            $("#deleteModal").addClass("hidden");
        });

        //list context menu
        $(document).on("contextmenu", ".listcontextmenu", function (e) {
            e.preventDefault();
            console.log("right click");
            let posX = e.pageX;
            let posY = e.pageY;

            $("#contextMenu").css({ "top": posY + "px", "left": posX + "px" }).removeClass("hidden");
            $(document).on("click", function () {
                $("#contextMenu").addClass("hidden");
            });
            let id = $(this).attr("id");
            $(".deletelist").attr("data-id", id);

        });

        // $(document).on("click", ".delete", function (e) {
        //     $("#deleteModal").removeClass("hidden");
        // });
    </script>
</body>

</html>