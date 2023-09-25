
        function print_page() {
            var ButtonControl = document.getElementById("prints");
            var ButtonControlTwo = document.getElementById("homes");
            ButtonControl.style.visibility = "hidden";
            ButtonControlTwo.style.visibility="hidden";
            window.print();
            ButtonControl.style.visibility = "visible";
            ButtonControlTwo.style.visibility="visible";
        }

