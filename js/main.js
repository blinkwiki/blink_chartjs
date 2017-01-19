function show(ctrl)
{
    // replace the hidden class 'null' with '' in the  element's
    // ctrl.className = ctrl.className.replace(/ hide/, '');
    ctrl.style.display = '';
}

function hide(ctrl)
{
    // append the hidden class 'null' to the element's class
    //ctrl.className += ' hide';
    ctrl.style.display = 'none';

}

function show_hide(ctrl, lock)
{
    if (typeof ctrl === 'string')
    {
        ctrlid = ctrl;
        var ctrl = document.getElementById(ctrlid);
    }
    
    {
    
    // append space: to ensure that the needle ' hide' works in the haystack
    var class_name = ' ' + ctrl.className;
    
    if (
        lock === undefined
        || lock == ''
    )
    {
        lock = '';
    
        // if the hidden class is found in the element's class
        //if (class_name.search(' hide') > 0)
        if (ctrl.style.display == 'none')
        {
            // show the element 
            show(ctrl);
        }
        else
        {
            // hide the element
            hide(ctrl);
        }
    }
    else
    {
        if (lock == 'show')
        {
            // show the element 
            show(ctrl);
        }
        else if (lock == 'hide')
        {
            // hide the element
            hide(ctrl);
        }
    }
    }
    
}

function multi_show_hide(ctrlname, lock)
{
    
    // get all the elements by the name
    var ctrls = document.getElementsByName(ctrlname);
    
    for (i=0; i<ctrls.length; i++)
    {
        // show or hide the element
        show_hide(ctrls[i], lock);
    }
    
}

function insta_src(src_val, tr_name)
{
    var space_chr = ' ';

    // start the searc result counter
    var res_count = 0;

    // get all the row elements to search
    var rows = document.getElementsByName(tr_name);

    // loop through all the rows and search

    // make sure the rows or elements are more than 0
    if (rows.length > 0)
    {

        for (i=0; i<rows.length; i++)
        {
            // define the search haystack:
            // i.e. the current row's
            // innerHTML property in lower case
            var haystack = space_chr + rows[i].innerHTML.toLowerCase();

            // define the needle: the input value also in lower
            // case
            var needle = src_val.toLowerCase();

            if (
                // if the needle is found in the haystack
                (haystack.search(needle) > 0)
                
                // and the needle is not a space, we want
                // alphanumeric characters in the search
                && (needle != space_chr)
            )
            {
                // set the style.display state as null:
                // i.e. to show the element
                dstate = '';

                // increment the search result counter
                res_count++;
            }
            else
            {
                // set the style.display state as none:
                // i.e. to hide the element
                dstate = 'none';

                // ensure that the needle is not null
                if (needle == '')
                {
                    // if so, reset the style.display
                    // state to null: show
                    dstate = '';
                }
            }

            // set the current row's style.display
            // attribute
            rows[i].style.display = dstate;

            // set the result counter
            res_counter.innerHTML = '';
            if (res_count > 0)
            {
                res_counter.innerHTML = res_count + ' results';
            }
        }
    }
}

/**
* 
* This function copies the selected option value and text
* to the appropriate HTML elements
* 
* the selected option's value is copied to the value property
* of the HTML element that will hold the value to be saved
* 
* the selected option's text is copied to the innerHTML attribute
* of the HTML element that displays the selected result to the user
* 
*/
function select_global_option()
{
    // select the value
    var ctrl_value = document.getElementById(g_value.value);
    ctrl_value.value = g_res_box.options[g_res_box.selectedIndex].value;
                        
    // show the title
    var ctrl_text = document.getElementById(g_text.value);
    ctrl_text.innerHTML = g_res_box.options[g_res_box.selectedIndex].text;
                    
    // hide the box
    show_hide(g_bulk_box);
                        
}

/**
*
* This function opens the global select box and loads up the options
* as defined by the list option command
*
* link is the HTML element that was clicked to open the global select box
*
* lopt is the command for generating options. For example,
* 'lopt_airports' will populate the global select box with airports
*
*/
function open_global_select(link, lopt)
{
    // initialise the list option parameter
    if (lopt === undefined) lopt = '';
        
    // show the bulk search box
    show_hide(g_bulk_box, 'show');
    
    // set the global value and text elements
    g_value.value = link.getAttribute('data-value');
    g_text.value = link.id;

    // populate the global select box using AJAX
    gen_ajax_opt(lopt);
}


/*(chmode_btn_line.addEventListener('click', function (event) {
    chart_mode_click('line');
});
chmode_btn_bar.addEventListener('click', function (event) {
    chart_mode_click('bar');
});*/
