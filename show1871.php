<?php
require_once('head.inc.php');
require_once('setdefault.inc.php');
require_once('replaceit_xml.inc.php');
require_once('oversigt_1871.inc.php');

define('CHAP_PER_LINE', 7);


function pagination($book, $kapitel) {
    global $chaptype, $chap;

    $outline_style = 'btn-outline-danger';

    echo "  <div style=\"margin-left: auto; margin-right: auto;\">\n";

    $chcount = count($chap[$book]);
    for ($chix=0; $chix < $chcount; ++$chix) {
        $chno = $chap[$book][$chix];
        echo "    <a href=\"show1871.php?bog=$book&kap=$chno\" "
            . "class=\"mt-1 mb-1 ml-0 mr-0 btn chap-btn "
            . ($chno==$kapitel ? $outline_style : 'btn-danger')
            . "\">$chno</a>\n";
    }
    echo "  </div>\n";
}

if (!isset($_GET['bog']) || !isset($_GET['kap']) || !is_numeric($_GET['kap'])) {
    echo "<pre>Forkerte parametre</pre>";
    die;
}
$kap = intval($_GET['kap']);
$bog = $_GET['bog'];
$fra = isset($_GET['fra']) && is_numeric($_GET['fra']) ?  intval($_GET['fra']) : 0;
$til = isset($_GET['til']) && is_numeric($_GET['til']) ?  intval($_GET['til']) : 0;


makeheadstart($abbrev[$bog] . ' ' . $kap, true);
?>
    <style type="text/css">
    .bibletext {
        font-family: <?= $allfonts[$_SESSION['font']] ?>;
    }

    span.verseno {
        vertical-align: super;
        font-size: x-small;
    }

    h2 {
        font-size: large;
        font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
        text-transform: none;
    }

    h2 small {
      font-size: 75%;
      color: #333333;
      font-weight: 700;
    }


    /* Taken in part from XKCD */
    /* the reference tooltips style starts here */

    .ref {
        position: relative;
        vertical-align: baseline;
    }

    .refnum, .refnumhead {
        position: relative;
        left: 2px;
        bottom: 1ex;
        color: #005994;
        font-size: .7em;
        font-weight: bold;
        text-decoration: underline;
        text-transform: lowercase;
        cursor: pointer;
    }

    .refbody, .refbodyhead {
        text-indent: 0;
        font-size: small;
        font-weight: normal;
        line-height: 1.1;
        display: block;
        border: 1px solid;
        border-radius: 4px;
        padding: 5px;
        background-color: lightblue;
    }

    div.paragraph {
        text-indent: 2em;
        display: block;
    }

    </style>

    <script>
    $(function() {
            <?php if ($_SESSION['showverse']=='on'): ?>
                $('.verseno').show();
            <?php else: ?>
                $('.verseno').hide();
            <?php endif; ?>

            <?php if ($_SESSION['showchap']=='on'): ?>
                $('.chapno').show();
            <?php else: ?>
                $('.chapno').hide();
            <?php endif; ?>

            <?php if ($_SESSION['showh2']=='on'): ?>
                $('h2').show();
            <?php else: ?>
                $('h2').hide();
            <?php endif; ?>

            <?php if ($_SESSION['showfna']=='on'): ?>
                $('.refa').show();
            <?php else: ?>
                $('.refa').hide();
            <?php endif; ?>

            <?php if ($_SESSION['showfn1']=='on'): ?>
                $('.ref1').show();
            <?php else: ?>
                $('.ref1').hide();
            <?php endif; ?>

            <?php if ($_SESSION['oneline']=='on'): ?>
                $('.paragraph').css('display','inline');
                $('.verseno').before('<br class="versebreak">');
            <?php endif; ?>

            <?php if ($_SESSION['godsname']=='HERREN'): ?>
                $('.thename').html('H<small>ERREN</small>');
                $('.thenames').html('H<small>ERRENS</small>');
                $('.thenamev').html('H<small>ERRE</small>');
                $('.thenamevs').html('H<small>ERRES</small>');
            <?php elseif ($_SESSION['godsname']=='Herren'): ?>
              $('.thename').text('Herren');
              $('.thenames').text('Herrens');
              $('.thenamev').text('Herre');
              $('.thenamevs').text('Herres');
            <?php else: ?>
              $('.thename').text('<?= $_SESSION['godsname'] ?>');
              $('.thenames').text('<?= $_SESSION['godsname'].'s' ?>');
              $('.thenamev').text('<?= $_SESSION['godsname'] ?>');
              $('.thenamevs').text('<?= $_SESSION['godsname'].'s' ?>');
            <?php endif; ?>


        $(".refbodyhead").hide();
        $(".refbody").hide();
        $(".refnum").click(function(event) {
            $(this.nextSibling).toggle();
            event.stopPropagation();
        });
        $(".refnumhead").click(function(event) {
            $(".refbodyhead").toggle();
            event.stopPropagation();
        });
        $("body").click(function(event) {
            $(".refbodyhead").hide();
            $(".refbody").hide();
        });



    });
    </script>

<?php
makeheadend();
makemenus(null);
?>

    <div class="container">
      <div class="row">
        <div class="col-lg-9 col-xl-8">
          <?php $text = replaceit_XML::replaceit("tekst/DA_OT1871.OSIS.xml", $inxml[$bog], $kap, $fra, $til); ?>
          <div class="card mt-4">
            <h1 class="card-header bg-warning"><?= $title[$bog] ?>, <?= $chaptype[$bog]?> <?=$kap?></h1>
            <div class="card-body bibletext">
              <?= $text ?>
            </div>
          </div>
        </div>

        <!-- Chapter chooser displayed at right for size lg and xl -->
        <div class="d-none d-lg-block col-lg-3 col-xl-4">
          <div class="card mt-4">
            <h1 class="card-header bg-info text-light">Vælg <?= $chaptype[$bog] ?></h1>
            <div class="card-body pl-xl-4 pl-lg-1 pr-0">
              <?php pagination($bog,$kap); ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Chapter chooser displayed at bottom for size xs, sm, and md -->
      <div class="row justify-content-center d-flex d-lg-none">
        <div class="col-sm-8 col-md-6">
          <div class="card mt-3">
            <h1 class="card-header bg-info text-light">bVælg <?= $chaptype[$bog] ?></h1>
            <div class="card-body pl-1 pl-sm-3 pr-0">
              <?php pagination($bog,$kap); ?>
            </div>
          </div>
        </div>
            </div>

      <div class="row">
        <div class="offset-xl-2 col-xl-4
                    offset-lg-2 col-lg-5
                    offset-md-3 col-md-6
                    offset-sm-2 col-sm-8">
          <div class="card mt-3">
            <h1 class="card-header bg-info text-light">Status for dette kapitel</h1>
            <div class="card-body">
              Denne tekst er den autoriserede oversættelse fra 1871 af Det Gamle Testamente.
            </div>
          </div>
        </div>
      </div>

    </div><!--End of container-->

<?php
endbody();
