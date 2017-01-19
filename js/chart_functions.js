function select_chart()
{
    show_hide('sel_mini_chart');
    show_hide('div_mini_chart');
}
    
function change_chart()
{
    select_chart();
    
    // the chart name is the value of the currently selected option
    chart_name = sel_mini_chart.options[sel_mini_chart.selectedIndex].value;
    
    // hide all rows in the table
    multi_show_hide('chart_tables', 'hide');
    
    // show only the tables belonging to the chart name
    show_hide('chart_table_header_'+chart_name, 'show');
    show_hide('chart_table_'+chart_name, 'show');
    
    // change the title of the chart
    div_mini_chart.innerHTML = ''
            + '<i class=\'fa fa-th-large\'></i>'
            + '<span class=\'fw_b\'> &nbsp; '
            + sel_mini_chart.options[sel_mini_chart.selectedIndex].text + '</span>'
    ;
            
    // draw the graph represented by the chart name
    draw_graph(chart_name);
            
}

function toggle_chart_link_class(link_id)
{
    // toggle the header class
    {
        if (link_id.className == 'red')
        {
            link_id.className = '';
        }
        else
        {
            link_id.className = 'red';
        }
    }
}
                
function randomScalingFactor()
{
    return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
}

function randomColorFactor()
{
    return Math.round(Math.random() * 255);
}

function randomColor()
{
    return 'rgba(' + randomColorFactor() + ',' + randomColorFactor() + ',' + randomColorFactor() + ',.7)';
}
                
function set_y_trends(lnk_id)
{
    // toggle chart link class
    toggle_chart_link_class(lnk_id);
    
    // reset the chart data
    var chart_name = sel_mini_chart.options[sel_mini_chart.selectedIndex].value;
    // alert(chart);
    
    // redraw the chart
    //draw_graph(lnk_id.name);
    draw_graph(chart_name);
    
}

// splice chart data set
function splice_data_set(ch_data, set_unset)
{
    var splice_i = 0;
    for (i=0; i<set_unset.length; i++)
    {
        // alert(set_unset[i]+' '+i);
        if (set_unset[i] == 0)
        {
            ch_data.datasets.splice(splice_i, 1);
        }
        else
        {
            splice_i++;
        }
    }
    return ch_data;
}
/*function splice_data_set(ch_data)
{
    
    var set_unset = new Array(ch_data.datasets.length);
        
    var yr_link_start_i = 0;
    var trend_links = document.getElementsByName('trend_links');
    for (i=0; i<set_unset.length; i++)
    {
                
        //lnk_id = yr_link_start_i + i;
        //lnk_id = 'y'+lnk_id+'_2';
        //lnk = document.getElementById(lnk_id);
        
        // get the item from the trend links
        lnk = trend_links[i]; //document.getElementById(lnk_id);
        
        set_unset[i] = 1;
        if (lnk.className == 'red')
        {
            set_unset[i] = 0;
        }
    }
    
    var splice_i = 0;
    for (i=0; i<set_unset.length; i++)
    {
        // alert(set_unset[i]+' '+i);
        if (set_unset[i] == 0)
        {
            ch_data.datasets.splice(splice_i, 1);
        }
        else
        {
            splice_i++;
        }
    }
    return ch_data;
}*/

function show_hide_chart_table(mode)
{
    multi_show_hide('div_visual', 'hide');
    if (mode == 'table')
    {
        show_hide('div_table', 'show');
        show_hide('div_table_tag', 'show');
    }
    else
    {
        show_hide('div_chart', 'show');
        show_hide('div_chart_tag', 'show');
    }
}

// resize the graph
function resize_graph()
{
        
    var canvas = document.getElementById("canvas").getContext("2d");
        
    //alert(canvas.width +' '+ div_chart.width);
    //canvas.width = div_chart.width;
        
    canvas.canvas.height = 250;
    canvas.canvas.width = div_chart.clientWidth; //550;
        
}
    
function draw_graph(chart)
{
    // get the chart
    if (chart === undefined)
    {
        chart = 'state_aviation_data';
    }
                
    // get the chart
    chart_data = get_chart_data(chart);
        
    // resize the canvas
    resize_graph()
        
    // redraw
    var mode = hd_chart_mode.value;
    if (mode == 'line')
    {
        // draw lines
		var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(chart_data, lineOpt);
    }
    else
    {
        // draw bars
        var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(chart_data);
    }
        
}
        
function change_graph_mode(mode)
{
    // set the value of the hidden input element with the chart mode
    hd_chart_mode.value = mode;
    
    // get the chart name from the chart selector select box
    // the currently selected chart on the list
    var chart = sel_mini_chart.options[sel_mini_chart.selectedIndex].value;
    
    // get the chart data
    chart_data = get_chart_data(chart);
        
    // resize the canvas
    resize_graph();

    if (mode == 'line')
    {
        // draw lines
        var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Line(chart_data, lineOpt);
    }
    else
    {
        // draw bars
        var myLine = new Chart(document.getElementById("canvas").getContext("2d")).Bar(chart_data);
    }
}

function chart_mode_click(mode)
{
    show_hide('chmode_btn_line');
    show_hide('chmode_btn_bar');
        
    // change the char mode to bar
    change_graph_mode(mode);
}

function set_x_values(id)
{
    var link_id = 'hdr'+id;
    toggle_chart_link_class(link_id);
    // reset the chart data
    // redraw the chart
}