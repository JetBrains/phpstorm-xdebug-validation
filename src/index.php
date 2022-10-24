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
</head>


<body class="bg-gray-100 font-sans leading-normal tracking-normal">

<nav id="header" class="fixed w-full z-10 top-0">

  <div id="progress" class="h-1 z-20 top-0" style="background:linear-gradient(to right, #4dc0b5 var(--scroll), transparent 0);"></div>

  <div class="w-full md:max-w-4xl mx-auto flex flex-wrap items-center justify-between mt-0 py-3">

    <div class="pl-4">
      <a class="text-gray-900 text-base no-underline hover:no-underline font-extrabold text-xl" href="#">
        Xdebug Validation
      </a>
    </div>

    <div class="w-full flex-grow lg:flex lg:items-center lg:w-auto hidden lg:block mt-2 lg:mt-0 bg-gray-100 md:bg-transparent z-20"
         id="nav-content">
      <ul class="list-reset lg:flex justify-end flex-1 items-center">
        <li class="mr-3">
          <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-3" href="https://github.com/JetBrains/phpstorm-xdebug-validation">GitHub</a>
        </li>
        <li class="mr-3">
          <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-2 px-3" href="https://www.jetbrains.com/help/phpstorm/validating-the-configuration-of-the-debugging-engine.html">Help</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<!--Container-->
<div class="container w-full md:max-w-3xl mx-auto pt-20">

  <div class="w-full px-4 md:px-6 text text-gray-800 leading-normal" style="font-family:Georgia,serif;">

    <!--Title-->
    <div class="font-sans">
      <h1 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-2 text-3xl md:text-4xl">PhpStorm Xdebug Validation Report</h1>
    </div>

    <!--PhpStorm Xdebug Content-->
    <?php
    require_once "phar://phpstorm_debug_validator.phar/common.php";
    $content = XDebugValidator::run();
    $xml = simplexml_load_string($content);
    $simpleXMLElement = $xml->section;
    foreach ($xml->section as $row) {
        ?>
    <h4
      class="font-bold font-sans break-normal text-gray-900 pt-6 pb-2 text-3xl md:text-2xl"><?php echo $row->attributes()->section_name; ?></h4>
    <ul>
      <?php
          foreach ($row->attributes() as $a => $b) {
              if ($a != "section_name") {
                  ?>
          <li class="flex items-center pl-6">
            <svg class="w-4 h-4 mr-1.5 text-green-500 dark:text-green-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"
                 xmlns="http://www.w3.org/2000/svg">
              <path fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"></path>
            </svg>
            <?php echo $a, ' &#8594; ', stripslashes($b), "\n"; ?>
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
<!--/container-->

<footer class="bg-white border-t border-gray-400 shadow">
  <div class="container max-w-4xl mx-auto flex py-8">

    <div class="w-full mx-auto flex flex-wrap">
      <div class="flex w-full md:w-1/2 ">
        <div class="px-8">
          <h3 class="font-bold text-gray-900">About</h3>
          <p class="py-4 text-gray-600 text-sm">
            PhpStorm Xdebug Validation Script helps to analyze problems in Xdebug configuration. Besides the information shown on this page,
            you can configure PhpStorm to get more deep analysis of your settings.
            More information can be found in <a class="inline-block text-green-600 no-underline hover:text-green-900 hover:text-underline" href="https://www.jetbrains.com/help/phpstorm/validating-the-configuration-of-the-debugging-engine.html">PhpStorm Xdebug Validation Documentation</a>
          </p>
        </div>
      </div>

      <!--<div class="flex w-full md:w-1/2">-->
      <!--  <div class="px-8">-->
      <!--    <h3 class="font-bold text-gray-900">Social</h3>-->
      <!--    <ul class="list-reset items-center text-sm pt-3">-->
      <!--      <li>-->
      <!--        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="#">Add social link</a>-->
      <!--      </li>-->
      <!--      <li>-->
      <!--        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="#">Add social link</a>-->
      <!--      </li>-->
      <!--      <li>-->
      <!--        <a class="inline-block text-gray-600 no-underline hover:text-gray-900 hover:text-underline py-1" href="#">Add social link</a>-->
      <!--      </li>-->
      <!--    </ul>-->
      <!--  </div>-->
      <!--</div>-->
    </div>


  </div>
</footer>

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
    }
    else {
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
