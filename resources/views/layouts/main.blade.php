<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News App</title>
    <link rel="stylesheet" href="/css/main.css">
</head>

<style>
#myBtn {
  display: none;
  position: fixed;
  bottom: 50px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: royalblue;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#myBtn:hover {
  background-color: #555;
}
</style>
<body class="font-sans bg-gray-900 text-white"> 
<button onclick="topFunction()" id="myBtn" title="Go to top">Top</button>
<nav class="border-b border-gray-800">
        <div class="container mx-auto flex flex-col md:flex-row items-center justify-between px-4 py-6">
            <ul class="flex flex-col md:flex-row items-center">
                <li>
                    <a href="/">
                        <img src="/img/news365.png" alt="logo" class="rounded-full w-32 h-32">
                    </a>
                </li>
                <li class="lg:ml-16 md:ml-6 mt-2 md:mt-0">
                    <a href="/" class="hover:text-gray-300">Internacionales</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="/?news=tech" class="hover:text-gray-300">Tecnología</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="/?news=pol" class="hover:text-gray-300">Política</a>
                </li>
                <li class="md:ml-6 mt-3 md:mt-0">
                    <a href="/?news=fin" class="hover:text-gray-300">Finanzas</a>
                </li>
            </ul>
                <div class="flex flex-col md:flex-row items-center">
                    <div class="relative">
                        <input type="text" class="bg-gray-800 text-sm rounded-full w-64 px-4 pl-8 py-1 md:mt-0 mt-4 focus:outline-none focus:shadow-outline" placeholder="Search">
                        <div class="absolute top-0">
                            <svg class="fill-current w-4 text-gray-500 md:mt-2 mt-6 ml-2" viewbox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                        </div>
                    </div>
                </div>
        </div>
    </nav>
    @yield('content')
    <script>
    //Get the button
    var mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
    if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
    }
    </script>
</body>
</html>