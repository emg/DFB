<?php

function replaceit($filename, $chapter, &$title, &$credit) {
    $txt = file_get_contents($filename);

    if (preg_match('/"/',$txt)) {
        echo "<h1>Fejl</h1>\n";
        echo "<p>Tekst indeholder dobbelt citationstegn.</p>\n";
        die;
    }

    preg_match_all('/!!<(.*)>!!/',$txt,$meta_matches);
    $credit = $meta_matches[1];

    preg_match('/===(.*)===/',$txt,$tit);
    $title = $tit[1];

    $from[] = '/!!<.*>!!/';
    $to[] = '';

    $from[] = '/===(.*)===/';
    $to[] = '';

    $from[] = '/==/';
    $to[] = '@';

    $from[] = '/>>>/';
    $to[] = '»›';

    $from[] = '/<<</';
    $to[] = '‹«';

    $from[] = '/>>/';
    $to[] = '»';

    $from[] = '/<</';
    $to[] = '«';

    $from[] = '/>/';
    $to[] = '›';

    $from[] = '/</';
    $to[] = '‹';

    $from[] = '/\'/';
    $to[] = '&rsquo;';

    $from[] = '/\*\*\*/';
    $to[] = '&nbsp;';

    $from[] = '/\*([^\*]+)\*/';
    $to[] = '<i>\1</i>';

    $from[] = '/\s*{E: *([^}]+)}/';
    $to[] = '<span class="ref refa"><span class="refnum">[REFALET]</span><span class="refbody">\1</span></span>';

    $from[] = '/\s*{T: *([^}]+)}/';
    $to[] = '<span class="ref ref1"><span class="refnum">[REFANUM]</span><span class="refbody">\1</span></span>';


    $from[] = '/JHVHs/i';
    $to[] = '<span class="thenames"></span>';

    $from[] = '/JHVHvs/i';
    $to[] = '<span class="thenamevs"></span>';

    $from[] = '/JHVHv/i';
    $to[] = '<span class="thenamev"></span>';

    $from[] = '/JHVH/i';
    $to[] = '<span class="thename"></span>';


    $from[] = '/HERRENS/';
    $to[] = '<span class="thenames"></span>';

    $from[] = '/HERRES/';
    $to[] = '<span class="thenamevs"></span>';

    $from[] = '/HERRE/';
    $to[] = '<span class="thenamev"></span>';

    $from[] = '/HERREN/';
    $to[] = '<span class="thename"></span>';

    $from[] = '/([^a-z])v([0-9]+)[\n ]*/';
    $to[] = '\1<span class="verseno"><span class="chapno">'.$chapter.':</span>\2</span>';

    $from[] = '/\n *\n/';
    $to[] = 'QQ';

    $from[] = '/\n/';
    $to[] = ' ';

    $from[] = '/QQ/';
    $to[] = "\n";

    $from[] = '/^ *([^\n@]+) *$/m';
    $to[] = '<div class="paragraph">\1</div>';

    $from[] = '/@([^@]+)@/';
    $to[] = '<h2>\1</h2>';

    $from[] = '/--/';
    $to[] = '&ndash;';

    $from[] = '/(\s)-(\s)/';
    $to[] = '\1&ndash;\2';

    $from[] = '/\.\.\./';
    $to[] = '…';
    
    $txt = preg_replace($from, $to, $txt);

    global $nextletter;
    $nextletter = 'a';
    global $nextnumber;
    $nextnumber = 1;

    $txt = preg_replace_callback('/REFALET/',
                                 function ($matches) {
                                     global $nextletter;
                                     return $nextletter++;
                                 }, $txt);

    $txt = preg_replace_callback('/REFANUM/',
                                 function ($matches) {
                                     global $nextnumber;
                                     return $nextnumber++;
                                 }, $txt);

    return  $txt;
  }