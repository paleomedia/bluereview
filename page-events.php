<?php get_header(); ?>
<div id="content" class='main--content'>
    <?php wp_nav_menu(array('container' => 'nav', 'theme_location' => 'category-filter', 'fallback_cb' => 'false')); ?>
    <div id="events" class="events-page article--single"><h1 class="page--title">Events Calendar</h1>
        <?php
        
        $blue_custom_options = get_option('blue_theme_options');
        $calendarfeed = esc_url($blue_custom_options['gCal']);
// Your private feed - which you get by right-clicking the 'xml' button in the 'Private Address' section of 'Calendar Details'.
if (!isset($calendarfeed)) {$calendarfeed = "https://www.google.com/calendar/feeds/boisestate.edu_gs6ue8qh2i1tvp94lsqi7jmtd4%40group.calendar.google.com/private-e06dad3bdc55920bd251c7532f042ea1/basic"; }

// Date format you want your details to appear
$dateformat="m.d.Y"; // 10 March 2009 - see http://www.php.net/date for details
$timeformat="g:iA"; // 12.15am

//Time offset - if times are appearing too early or too late on your website, change this.
$offset = -7;
// Adjust for daylight savings
$offset -= date("I");
$offset .= "hours"; // you can use "+1 hour" here for example
// you can also use $offset="now";

// How you want each thing to display.
// By default, this contains all the bits you can grab. You can put ###DATE### in here too if
// you want to, and disable the 'group by date' below.

$event_display.= "<article class='events'><h2> ###TITLE### </h2>";
$event_display.="<p class='events'>###DATE### | ###TIME###";
$event_display .= " | ###WHERE### </p><p class='description'>###DESCRIPTION###</p></article>";

// What happens if there's nothing to display
$event_error="<p>There are no events to display.</p>";

// The separate date header is here
$event_dateheader="";
$GroupByDate=false;
// Change the above to 'false' if you don't want to group this by dates,
// but remember to add ###DATE### in the event_display if you do.

// ...and how many you want to display (leave at 999 for everything)
$items_to_show=12;

// Change this to 'true' to see lots of fancy debug code
$debug_mode=false;

//
//End of configuration block
/////////

if ($debug_mode) {error_reporting (E_ALL); echo "<P>Debug mode is on.</p>";}

// Form the XML address.
$calendar_xml_address = str_replace("/basic","/full?singleevents=true&futureevents=true&orderby=starttime&sortorder=a",$calendarfeed); //This goes and gets future events in your feed.

if ($debug_mode) {
echo "<P>We're going to go and grab <a href='$calendar_xml_address'>this feed</a>.<P>";}

// Set the offset correctly
$offset=(strtotime("now")-strtotime($offset));
if ($debug_mode) {echo "Offset is ".$offset;}

if ( false === ( $cached_calendar = get_transient('blue_calendar_xml'))){
    $xml = simplexml_load_file($calendar_xml_address);
    set_transient('blue_calendar_xml', $xml->asXML(), 60*60*3); // expiration 60 sec = 1 min * 60 min = 1 hour * 3 hours
}
else{
    $xml = simplexml_load_string($cached_calendar);
}

if ($debug_mode) {echo "<P>Successfully retrieved it.</p>";}
$items_shown=0;
if ($xml != ""){
$xml->asXML();
}
?><?php

foreach ($xml->entry as $entry){
    $ns_gd = $entry->children('http://schemas.google.com/g/2005');
    $description = eregi_replace('(((f|ht){1}tp://)[-a-zA-Z0-9@:%_\+.~#?,&//=]+)','<a href="\\1">\\1</a>', $entry->content);
    
    //Do some niceness to the description
    //Make any URLs used in the description clickable: thanks Adam
    $description = preg_replace("~\n~", "<br />", $description, -1, $count);
    
    // Make email addresses in the description clickable: thanks, Bjorn
    $description = eregi_replace('([_.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3})','<a
href="mailto:\\1">\\1</a>', $description);
    
    // These are the dates we'll display
    $start = $ns_gd->when->attributes()->startTime;
    error_log(strlen((string)$start));
    if (strlen((string)$start) < 12){
        $gCalDate = date($dateformat, strtotime($ns_gd->when->attributes()->startTime));
        $gCalEndDate = date($dateformat, strtotime($ns_gd->when->attributes()->endTime)-1);
    }
    else{
        $gCalDate = date($dateformat, strtotime($ns_gd->when->attributes()->startTime)-$offset);
        $gCalEndDate = date($dateformat, strtotime($ns_gd->when->attributes()->endTime)-$offset);
    }
    
    $gCalStartTime = date($timeformat, strtotime($ns_gd->when->attributes()->startTime)-$offset);
    $gCalEndTime = date($timeformat, strtotime($ns_gd->when->attributes()->endTime)-$offset);

    // Now, let's run it through some str_replaces, and store it with the date for easy sorting later
    $temp_event=$event_display;
    $temp_dateheader=$event_dateheader;
    $temp_event=str_replace("###DESCRIPTION###",$description,$temp_event);
    $temp_event=str_replace("###TITLE###",$entry->title,$temp_event);
    $temp_dateheader=str_replace("###DATE###",$gCalDate,$temp_dateheader);
    if ($gCalDate == $gCalEndDate){
        $temp_event=str_replace("###DATE###",$gCalDate,$temp_event);
        $temp_event=str_replace("###TIME###",$gCalStartTime . " - " . $gCalEndTime,$temp_event);
    }
    else{
        $temp_event=str_replace("###DATE###",$gCalDate . " - " . $gCalEndDate,$temp_event);
        $temp_event=str_replace("###TIME### |", "" ,$temp_event);
    }
    $temp_event=str_replace("###WHERE###",$ns_gd->where->attributes()->valueString,$temp_event);
    $temp_event=str_replace("###LINK###",$entry->link->attributes()->href,$temp_event);
    $temp_event=str_replace("###MAPLINK###","http://maps.google.com/?q=".urlencode($ns_gd->where->attributes()->valueString),$temp_event);
    // Accept and translate HTML
    $temp_event=str_replace("&lt;","<",$temp_event);
    $temp_event=str_replace("&gt;",">",$temp_event);
    $temp_event=str_replace("&quot;","\"",$temp_event);

    if (($items_to_show>0 AND $items_shown<$items_to_show)) {
                if ($GroupByDate) {if ($gCalDate!=$old_date) { echo $temp_dateheader; $old_date=$gCalDate;}}
        echo $temp_event;
        $items_shown++;
    }
}

if (!$items_shown) { echo $event_error; }?>

        
    </div><!--/#articles -->
</div><!--/#main-content-front-->
<?php get_sidebar('single'); ?>
<?php get_footer(); ?>