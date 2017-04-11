$("select").on("click" , function() {
  
    $(this).parent('.custom-select').toggleClass('open');
    
  });

  $(document).mouseup(function (e)
  {
      var container = $('.custom-select');

      if (container.has(e.target).length === 0)
      {
          container.removeClass("open");
      }
  });


  $('#select-choice1').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  $('#select-choice2').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  $('#select-choice3').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  $('#select-choice4').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  $('#select-choice5').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  $('#select-choice7').on('click' , function() {
    
    var selection = $(this).children('option:selected').text(),
        labelFor = $(this).attr('id'),
        label = $(this).siblings('.label').children('span');
      
    label.html(selection);
      
  });

  
  