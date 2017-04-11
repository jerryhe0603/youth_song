(function($){
  
    var jump = function(e)
    {
       if (e){
           e.preventDefault();
           var target = $(this).attr("href");
       }else{
           var target = location.hash;
       }

       $('html,body').animate(
       {
           scrollTop: 0

       },1000,function()
       {
           location.hash = target;
       });

    }

    $('html, body').hide()

    $(document).ready(function()
    {
        $('a[href^="#"]').bind("click", jump);

        if (location.hash){
            setTimeout(function(){
                $('html, body').scrollTop(0).show()
                jump()
            }, 2000);
        }else{
          $('html, body').show()
        }
    });
  
})(jQuery)