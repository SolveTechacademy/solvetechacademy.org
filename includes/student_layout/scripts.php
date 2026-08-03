<script>

document.addEventListener("DOMContentLoaded",function(){

    const button=document.getElementById("menuToggle");

    const sidebar=document.querySelector(".sidebar");

    if(button){

        button.addEventListener("click",function(){

            sidebar.classList.toggle("show");

        });

    }

    document.addEventListener("click",function(e){

        if(window.innerWidth<=992){

            if(
                !sidebar.contains(e.target) &&
                !button.contains(e.target)
            ){

                sidebar.classList.remove("show");

            }

        }

    });

});

</script>