<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>PhpStorm Xdebug Validation</title>
    <meta name="author" content="name"/>
    <meta name="description" content="description here"/>
    <meta name="keywords" content="keywords,here"/>
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
</head>


<body class="bg-gray-100 font-sans leading-normal">
<div class="h-screen tracking-normal flex flex-col">
<nav id="header" class="sticky z-10 top-0 flex-none">

    <div id="progress" class="h-1 z-20 top-0"
         style="background:linear-gradient(to right, #4dc0b5 var(--scroll), transparent 0);"></div>

    <div class="lg:max-w-4xl mx-auto px-4 flex flex-nowrap items-center justify-between mt-0 py-3">

        <div class="pl-4 flex-grow">
            <a class="text-gray-900 text-base no-underline hover:no-underline font-extrabold text-xl" href="#">
                Xdebug Validation
            </a>
        </div>

        <div class="lg:items-center lg:w-auto flex mt-2 lg:mt-0 bg-gray-100 md:bg-transparent z-20"
             id="nav-content">
            <ul class="list-reset justify-end flex flex-column items-center">
                <li class="mr-3">
                    <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-3"
                       href="https://github.com/JetBrains/phpstorm-xdebug-validation">GitHub</a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-3"
                       href="https://www.jetbrains.com/help/phpstorm/validating-the-configuration-of-the-debugging-engine.html">Help</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!--Container-->
<div class="container w-full md:max-w-3xl mx-auto py-6 flex-grow">
    <div class="w-full px-4 md:px-6 text text-gray-800 leading-normal" style="font-family:Georgia,serif;">
        <!--Title-->
        <div class="font-sans">
            <h1 class="font-bold font-sans break-normal text-gray-900 pb-2 text-3xl md:text-4xl">PhpStorm Xdebug
                Validation Report</h1>
        </div>
        <!--PhpStorm Xdebug Content-->
        <?php
        require_once "common.php";
        $content = XDebugValidator::run();
        $xml = simplexml_load_string($content);
        $simpleXMLElement = $xml->section;
        foreach ($xml->section as $row) {
            ?>
        <h4
                class="font-bold font-sans break-normal text-gray-900 pt-6 pb-2 text-3xl md:text-2xl"><?php
                echo $row->attributes()->section_name; ?></h4>
        <ul>
            <?php
                foreach ($row->attributes() as $a => $b) {
                    if ($a != "section_name" && substr($b, 0, 29) != "This setting has been changed") {
                        ?>
                    <li class="flex items-center pl-6">
                        <?php
                            if (stripslashes($b) === "FAIL") { ?>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                 class="fill-current text-red-500 dark:text-red-400 mr-1.5">
                                <path
                                        d="M10 0.40625C4.6875 0.40625 0.40625 4.6875 0.40625 10C0.40625 15.3125 4.6875 19.625 10 19.625C15.3125 19.625 19.625 15.3125 19.625 10C19.625 4.6875 15.3125 0.40625 10 0.40625ZM10 18.5312C5.3125 18.5312 1.5 14.6875 1.5 10C1.5 5.3125 5.3125 1.5 10 1.5C14.6875 1.5 18.5312 5.3125 18.5312 10C18.5312 14.6875 14.6875 18.5312 10 18.5312Z"
                                ></path>
                                <path
                                        d="M12.875 7.125C12.6563 6.90625 12.3125 6.90625 12.0938 7.125L10 9.21875L7.90625 7.125C7.6875 6.90625 7.34375 6.90625 7.125 7.125C6.90625 7.34375 6.90625 7.6875 7.125 7.90625L9.21875 10L7.125 12.0937C6.90625 12.3125 6.90625 12.6562 7.125 12.875C7.21875 12.9687 7.375 13.0312 7.5 13.0312C7.625 13.0312 7.78125 12.9687 7.875 12.875L9.96875 10.7812L12.0625 12.875C12.1563 12.9687 12.3125 13.0312 12.4375 13.0312C12.5625 13.0312 12.7188 12.9687 12.8125 12.875C13.0313 12.6562 13.0313 12.3125 12.8125 12.0937L10.7813 10L12.875 7.90625C13.0625 7.6875 13.0625 7.34375 12.875 7.125Z"
                                ></path>
                            </svg>
                            <?php
                            } else { ?>
                            <svg width="20" height="20" viewBox="0 0 20 20"
                                 class="fill-current text-green-500 dark:text-green-400 mr-1.5">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"></path>
                            </svg>
                            <?php
                            } ?><?php
                        $a = str_replace('xdebug3', 'xdebug', str_replace('_', ' ', $a));
                        echo $a, ' &#8594; ', stripslashes($b), "\n"; ?>
                    </li>
                    <?php
                    }
                }
            ?>
            <!--<hr class="border-b-2 border-gray-300 mb-8 mx-4">-->
            <?php
        }
        ?>
        </ul>
    </div>
</div>
<footer class="bg-white text-center w-full lg:text-left">
  <div class="bg-white-100 text-gray-600 lg:max-w-4xl mx-auto px-4 flex">
    <details class="bg-white rounded group w-full" open>
        <summary class="list-none flex flex-wrap items-center cursor-pointer
    focus-visible:outline-none focus-visible:ring focus-visible:ring-pink-500
    rounded group-open:rounded-b-none group-open:z-[1] relative
    ">
            <h3 class="flex flex-1 pl-4 py-3 font-semibold md:text-xl pt-3">
                <svg class="w-7 h-7 mr-2 shrink-0" fill="currentColor" viewBox="0 0 20 20"
                     xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                          d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"
                          clip-rule="evenodd"></path>
                </svg>
                About
            </h3>
            <div class="flex w-10 items-center justify-center">
                <div class="border-8 border-transparent border-l-gray-600
        group-open:rotate-90 transition-transform origin-left pr-4
        "></div>
            </div>
        </summary>
        <div class="flex px-8">
            <div class="flex w-full md:w-1/2">
                <div class="">
                    <p class="text-gray-600 text-sm text-left">
                        PhpStorm Xdebug Validation Script helps to analyze problems in Xdebug configuration. Besides the
                        information shown on this
                        page,
                        you can configure PhpStorm to get more deep analysis of your settings.
                        More information can be found in <a
                                class="inline-block text-green-600 no-underline hover:text-green-900 hover:text-underline"
                                href="https://www.jetbrains.com/help/phpstorm/validating-the-configuration-of-the-debugging-engine.html">PhpStorm
                            Xdebug Validation Documentation</a>
                    </p>
                </div>
            </div>
            <div class="flex justify-end w-full md:w-1/2">
                <div class="">
                    <ul class="list-reset text-sm">
                        <li>
                            <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1"
                               href="https://github.com/JetBrains/phpstorm-xdebug-validation">GitHub</a>
                        </li>
                        <li>
                            <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1"
                               href="https://www.jetbrains.com/help/phpstorm/validating-the-configuration-of-the-debugging-engine.html">PhpStorm
                                Documentation</a>
                        </li>
                        <li>
                            <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1"
                               href="https://xdebug.org/docs/">Xdebug Documentation</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </details>
</div>
    <div class="text-gray-500 text-center py-3" style="background-color: rgba(0, 0, 0, 0);">
        © 2022 Copyright:
        <a class="text-gray-500" href="https://www.jetbrains.com">JetBrains</a>
    </div>
</footer>
</div>
<script>
    /* Progress bar */
    //Source: https://alligator.io/js/progress-bar-javascript-css-variables/
    var h = document.documentElement,
        b = document.body,
        st = 'scrollTop',
        sh = 'scrollHeight',
        progress = document.querySelector('#progress'),
        scroll
    var scrollpos = window.scrollY
    var header = document.getElementById("header")
    var navcontent = document.getElementById("nav-content")

    document.addEventListener('scroll', function () {

        /*Refresh scroll % width*/
        scroll = (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100
        progress.style.setProperty('--scroll', scroll + '%')

        /*Apply classes for slide in bar*/
        scrollpos = window.scrollY

        if (scrollpos > 10) {
            header.classList.add("bg-white")
            header.classList.add("shadow")
            navcontent.classList.remove("bg-gray-100")
            navcontent.classList.add("bg-white")
        } else {
            header.classList.remove("bg-white")
            header.classList.remove("shadow")
            navcontent.classList.remove("bg-white")
            navcontent.classList.add("bg-gray-100")

        }

    })

    //Javascript to toggle the menu
    document.getElementById('nav-toggle').onclick = function () {
        document.getElementById("nav-content").classList.toggle("hidden")
    }
</script>
</body>
</html>
