$(function() {

    // Custom scroll for chat
    $(".base__statistics, .base__games, .game__scroll, .table__scroll, .modal-table__wrap").nanoScroller({ alwaysVisible: true});


    $(document).ready(function() {

        // Custom scroll in nested game block
        //$('.base__games')[0].nanoscroller.reset();

        // Control all checkbox in data table 
        $('.table-check--all').click(function() {
        var isChecked = $(this).prop("checked");
        $('.table-base tr:has(td)').find('input[type="checkbox"]').prop('checked', isChecked);
        });
    
        $('.table-base tr:has(td)').find('input[type="checkbox"]').click(function() {
        var isChecked = $(this).prop("checked");
        var isHeaderChecked = $(".table-check--all").prop("checked");
        if (isChecked == false && isHeaderChecked)
            $(".table-check--all").prop('checked', isChecked);
        else {
            $('.table-base tr:has(td)').find('input[type="checkbox"]').each(function() {
            if ($(this).prop("checked") == false)
                isChecked = false;
            });
            $(".table-check--all").prop('checked', isChecked);
        }
        });
    });

    // Show popup with check info
    $(document).ready(function() {
        var checkboxes = $('.table-base tr td input[type="checkbox"], .table-check--all');
        var checkboxesArray = $('.table-base tr td input[type="checkbox"]');
            
        checkboxes.change(function(){
            var countCheckedCheckboxes = checkboxesArray.filter(':checked').length;   
            $('.check-counter').text(countCheckedCheckboxes);
            $('.checker').css({
                'visibility': 'visible',
                'opacity': 1,
                'transition' : '.5s ease all'
            });
        });

        $('.checker__all, .table-check--all').click(function() {
            $('.table-base tr td input[type="checkbox"], .table-check--all').prop('checked', 'checked');
            var countCheckedCheckboxes = checkboxesArray.filter(':checked').length;   
            $('.check-counter').text(countCheckedCheckboxes);
            
        });

        $('.checker__cancel').click(function() {
            $('.table-base tr td input[type="checkbox"], .table-check--all').prop('checked', false);
            var countCheckedCheckboxes = checkboxesArray.filter(':checked').length;   
            $('.check-counter').text(countCheckedCheckboxes);
            $('.checker').css({
                'visibility': 'hidden',
                'opacity': 0
            });
        });

        
        // Show calendar popup
        $('.calendar__inp').click(function() {
            $('.calendar-box').css({
                'visibility': 'visible',
                'opacity': 1,
                'z-index': 5,
                'transition' : '.5s ease all'
            });

            $(".calendar-box__body").flatpickr({
                "locale": "ru",
                inline: true,
                showMonths: 2,
                dateFormat: "d.m.y",
                altFormat: "d.m.y",
                ariaDateFormat: "d.m.y",
                mode: "range",
                onChange: function(selectedDates, dateStr, instance) {
                    $(".calendar__inp").val(dateStr);
                }
            });
        });
        
        // Close calendar popup
        $('.calendar-box__close').click(function() {
            $('.calendar-box').css({
                'visibility': 'hidden',
                'opacity': 0,
                'z-index': -1
            });
        });

        // Choose fast link in calendar popup
        var checkboxesPeriod = $('.choose-period__inp');
        checkboxesPeriod.change(function(){
            var checkboxesPeriodValue = checkboxesPeriod.filter(':checked').next().text();   
            $(".calendar__inp").val(checkboxesPeriodValue);
        });
        
        // Open edit modal
        $('.table-base__edit').click(function() {
            $('body').css({
                'overflow': 'hidden'
            });

        });

        // Close edit modal
        $('body').on('click', '.modal__close-link', function() {
            $('.modal, .modal__container').css({
                'visibility': 'hidden',
                'opacity': 0
            });
            $('body').css({
                'overflow': 'visible'
            });
        });

        // Close edit modal when user click on window
        var modal = document.getElementsByClassName('modal__container')[0];

        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
        if (event.target == modal) {
            $('.modal, .modal__container').css({
                'visibility': 'hidden',
                'opacity': 0
            });
        }
        });
        

        
        var selectOprion;
        
        

        $('select').selectric({
            onInit: function(element)
            {
               var el = $(this);
            var name = el.attr('name');
            var val = el.val();


            var selectOption = $(element).find('option:selected').attr("class");
            
            var data = $(this).data('selectric');
            $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass (function (index, className) {
                return string = (className.match (/(^|\s)bgn-text bgn-text--\S+/g) || []).join(' ');
            });

            $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(selectOption);
            if(!$(element).find('option:selected').attr('class')) {
                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(' ');
            }



            $('#table1-body input[name="user[]"]:checked').each(function(indx, el) {
                var currentElement = $(el);
                currentElement.closest('tr').find('select[name="'+ name +'"]').val(val);
                $('#table1-body select[name="'+ name +'"]').selectric('refresh');

                var selectOption = currentElement.closest('tr').find('select[name="'+ name +'"]').find('option:selected').attr("class");

                var data = currentElement.closest('tr').find('select[name="'+ name +'"]').data('selectric');
                console.log(data);
                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass (function (index, className) {
                    return string = (className.match (/(^|\s)bgn-text bgn-text--\S+/g) || []).join(' ');
                });

                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(selectOption);
                if(!$(element).find('option:selected').attr('class')) {
                    $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(' ');
                }

            });
            },
            labelBuilder: function(currItem) {
              return (currItem.value.length ? '<span class="bgn-' + currItem.class + '"></span>' : '') + currItem.text;
            }
        });


        // Sorting in data table
        // $('#table1-top').tablesorter({
        //     cssHeader: 'loh',
        //     headers: {
        //         0: { sorter: false},
        //         2: { sorter: false}
        //     }
        // });
    
        
    });

    // $('select').selectric();
    //
    // $('select').on('selectric-close', function(e){
    //     var data = $(this).data('selectric');
    //     var data2 = $(data.element).closest('.' + data.classes.wrapper).find('.selected').attr('class').replace('selected','');
    //     console.log(data.element.selectedOptions);
    //     $(data.element).closest('.' + data.classes.wrapper).find('.label').addClass(data2);
    // });
    var hasChanged;
    $("select").selectric({
         onBeforeOpen: function() {
                var data = $(this).data('selectric');
                if($('body').find('.changedStatus').length > 0 && !$(data.element).closest('.' + data.classes.wrapper).find('.label').hasClass('changedStatus'))
                {
                    alert('Уже выбран один вариант!');
                    hasChanged = false;
                }
                else
                {
                    hasChanged = true;
                }
         },
        onChange: function(element){
            
            if(hasChanged)
            {
            var el = $(this);
            var name = el.attr('name');
            var val = el.val();


            var selectOption = $(element).find('option:selected').attr("class");
            
            var data = $(this).data('selectric');
            $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass (function (index, className) {
                return string = (className.match (/(^|\s)bgn-text bgn-text--\S+/g) || []).join(' ');
            });

            $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(selectOption);
            $(data.element).closest('.' + data.classes.wrapper).find('.label').addClass('changedStatus');
            if(!$(element).find('option:selected').attr('class')) {
                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(' ');
            }

          //  el.closest('tr').find('input[type="checkbox"]').attr('checked', true);


            $('#table1-body input[name="user[]"]:checked').each(function(indx, el) {
                var currentElement = $(el);
                currentElement.closest('tr').find('select[name="'+ name +'"]').val(val);
                $('#table1-body select[name="'+ name +'"]').selectric('refresh');

                var selectOption = currentElement.closest('tr').find('select[name="'+ name +'"]').find('option:selected').attr("class");

                var data = currentElement.closest('tr').find('select[name="'+ name +'"]').data('selectric');
                console.log(data);
                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass (function (index, className) {
                    return string = (className.match (/(^|\s)bgn-text bgn-text--\S+/g) || []).join(' ');
                });

                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(selectOption);

                if(!$(element).find('option:selected').attr('class')) {
                    $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(' ');
                }

            });

        }
        }
    });
});
