<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../index.php');    
}
$xml = file_get_contents('http://myanimelist.net/malappinfo.php?u=' . $_SESSION['user'] . '&status=all&type=anime');
$data = new SimpleXMLElement($xml);
?>
<!DOCTYPE html>
<html class="mdc-typography">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $_SESSION['user']; ?>'s Anime List</title>
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/theme.css">
    <link rel="stylesheet" href="../styles/anime_list.css">
    <link rel="icon" type="image/png" href="/images/favicon/favicon.png">
    <link rel="shortcut_icon" href="/images/favicon/favicon.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#0d47a1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
</head>
<body>

    <header class="mdc-toolbar mdc-toolbar--fixed">
      <div class="mdc-toolbar__row">
        <section class="mdc-toolbar__section mdc-toolbar__section--align-start">
            <!-- <a href="#" class="material-icons mdc-toolbar__icon--menu menu">menu</a> -->
            <span class="mdc-toolbar__title">Watching</span>
        </section>
        <section class="mdc-toolbar__section mdc-toolbar__section--align-end" role="toolbar">
            <!-- <form id="mal_search">
                <div class="mdc-textfield">
                    <input type="text" id="search_query" name="search_query" class="mdc-textfield__input">
                    <label class="mdc-textfield__label" for="search_query">Search</label>
                </div>
                <input type="submit" class="mdc-button" value="Search" />
            </form> -->
            <a href="logout.php" class="mdc-typography--body1 mdc-toolbar__icon">Logout</a>
        </section>
      </div>
    </header>

    <div class="mdc-toolbar-fixed-adjust">

    <main>
        <nav id="anime_list_nav" class="mdc-tab-bar mdc-tab-bar--icons-with-text">
            <a id="watching_button" class="mdc-tab mdc-tab--with-icon-and-text mdc-tab--active">
                <i class="material-icons mdc-tab__icon" aria-hidden="true">play_arrow</i>
                <span class="mdc-tab__icon-text">Watching</span>
            </a>
            <a id="completed_button" class="mdc-tab mdc-tab--with-icon-and-text">
                <i class="material-icons mdc-tab__icon" aria-hidden="true">check</i>
                <span class="mdc-tab__icon-text">Completed</span>
            </a>
            <a id="on_hold_button" class="mdc-tab mdc-tab--with-icon-and-text">
                <i class="material-icons mdc-tab__icon" aria-hidden="true">pause</i>
                <span class="mdc-tab__icon-text">On Hold</span>
            </a>
            <a id="dropped_button" class="mdc-tab mdc-tab--with-icon-and-text">
                <i class="material-icons mdc-tab__icon" aria-hidden="true">remove</i>
                <span class="mdc-tab__icon-text">Dropped</span>
            </a>
            <a id="ptw_button" class="mdc-tab mdc-tab--with-icon-and-text">
                <i class="material-icons mdc-tab__icon" aria-hidden="true">add</i>
                <span class="mdc-tab__icon-text">Plan to Watch</span>
            </a>
            <!-- <span class="mdc-tab-bar__indicator"></span> -->
        </nav>
        <div id="anime_list" class="mdc-typography--body1">
            <div class="mdc-card">
                <div class="mdc-list-group">
                    <section id="watching_list">
                        <ul class="mdc-list mdc-list--two-line mdc-list--dense">
                            <?php
                            foreach($data->anime as $a) {
                                if ($a->my_status == '1') {
                                    echo '<li class="mdc-list-item anime_list_item">';
                                    echo '<img class="mdc-list-item__start-detail anime_list_thumb" src=' . $a->series_image . '>';
                                    echo '<span class="mdc-list-item__text">' . $a->series_title;
                                    echo '<span class="mdc-list-item__text__secondary"><i class="material-icons list-icon">star_rate</i> ' . $a->my_score . '/10  <i class="material-icons list-icon">playlist_add_check</i> ' . $a->my_watched_episodes . '/' . $a->series_episodes . '</span>';
                                    echo '</span></li><hr class="mdc-list-divider">';
                                }
                            }
                            ?>
                        </ul>
                    </section>

                    <section id="completed_list">
                        <ul class="mdc-list mdc-list--two-line mdc-list--dense">
                            <?php
                            foreach($data->anime as $a) {
                                if ($a->my_status == '2') {
                                    echo '<li class="mdc-list-item anime_list_item">';
                                    echo '<img class="mdc-list-item__start-detail anime_list_thumb" src=' . $a->series_image . '>';
                                    echo '<span class="mdc-list-item__text">' . $a->series_title;
                                    echo '<span class="mdc-list-item__text__secondary"><i class="material-icons list-icon">star_rate</i> ' . $a->my_score . '/10  <i class="material-icons list-icon">playlist_add_check</i> ' . $a->my_watched_episodes . '</span>';
                                    echo '</span></li><hr class="mdc-list-divider">';
                                }
                            }
                            ?>
                        </ul>
                    </section>

                    <section id="on_hold_list">
                        <ul class="mdc-list mdc-list--two-line mdc-list--dense">
                            <?php
                            foreach($data->anime as $a) {
                                if ($a->my_status == '3') {
                                    echo '<li class="mdc-list-item anime_list_item">';
                                    echo '<img class="mdc-list-item__start-detail anime_list_thumb" src=' . $a->series_image . '>';
                                    echo '<span class="mdc-list-item__text">' . $a->series_title;
                                    echo '<span class="mdc-list-item__text__secondary"><i class="material-icons list-icon">star_rate</i> ' . $a->my_score . '/10  <i class="material-icons list-icon">playlist_add_check</i> ' . $a->my_watched_episodes . '/' . $a->series_episodes . '</span>';
                                    echo '</span></li><hr class="mdc-list-divider">';
                                }
                            }
                            ?>
                        </ul>
                    </section>

                    <section id="dropped_list">
                        <ul class="mdc-list mdc-list--two-line mdc-list--dense">
                            <?php
                            foreach($data->anime as $a) {
                                if ($a->my_status == '4') {
                                    echo '<li class="mdc-list-item anime_list_item">';
                                    echo '<img class="mdc-list-item__start-detail anime_list_thumb" src=' . $a->series_image . '>';
                                    echo '<span class="mdc-list-item__text">' . $a->series_title;
                                    echo '<span class="mdc-list-item__text__secondary"><i class="material-icons list-icon">star_rate</i> ' . $a->my_score . '/10  <i class="material-icons list-icon">playlist_add_check</i> ' . $a->my_watched_episodes . '/' . $a->series_episodes . '</span>';
                                    echo '</span></li><hr class="mdc-list-divider">';
                                }
                            }
                            ?>
                        </ul>
                    </section>

                    <section id="ptw_list">
                        <ul class="mdc-list mdc-list--two-line mdc-list--dense">
                            <?php
                            foreach($data->anime as $a) {
                                if ($a->my_status == '6') {
                                    echo '<li class="mdc-list-item anime_list_item">';
                                    echo '<img class="mdc-list-item__start-detail anime_list_thumb" src=' . $a->series_image . '>';
                                    echo '<span class="mdc-list-item__text">' . $a->series_title;
                                    echo '<span class="mdc-list-item__text__secondary"><i class="material-icons list-icon">star_rate</i> ' . $a->my_score . '/10  <i class="material-icons list-icon">playlist_play</i> ' . $a->series_episodes . '</span>';
                                    echo '</span></li><hr class="mdc-list-divider">';
                                }
                            }
                            ?>
                        </ul>
                    </section>

                </div>
            </div>
            <div class="mobile_spacer"></div>
        </div>
        <div id="search_results" class="mdc-typography--body1">
        </div>
    </main>        

    </div>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<!-- <script>
  (function() {
    // Delay initialization within development until styles have loaded
    setTimeout(initInteractiveLists, 250);
    function initInteractiveLists() {
      var interactiveListItems = document.querySelectorAll('.anime_list_item');
      for (var i = 0, li; li = interactiveListItems[i]; i++) {
        mdc.ripple.MDCRipple.attachTo(li);
        // Prevent link clicks from jumping demo to the top of the page
        li.addEventListener('click', function(evt) {
          evt.preventDefault();
        });
      }
    }
  })();
</script> -->
<script>
$('#watching_button').click(function() {
    $('#watching_list').css('display', 'block');
    $('#completed_list').css('display', 'none');
    $('#on_hold_list').css('display', 'none');
    $('#dropped_list').css('display', 'none');
    $('#ptw_list').css('display', 'none');
    $('.mdc-toolbar__title').html('Watching');
    window.scrollTo(0, 0);
});
$('#completed_button').click(function() {
    $('#completed_list').css('display', 'block');
    $('#watching_list').css('display', 'none');
    $('#on_hold_list').css('display', 'none');
    $('#dropped_list').css('display', 'none');
    $('#ptw_list').css('display', 'none');
    $('.mdc-toolbar__title').html('Completed');
    window.scrollTo(0, 0);
});
$('#on_hold_button').click(function() {
    $('#on_hold_list').css('display', 'block');
    $('#watching_list').css('display', 'none');
    $('#completed_list').css('display', 'none');
    $('#dropped_list').css('display', 'none');
    $('#ptw_list').css('display', 'none');
    $('.mdc-toolbar__title').html('On Hold');
    window.scrollTo(0, 0);
});
$('#dropped_button').click(function() {
    $('#dropped_list').css('display', 'block');
    $('#watching_list').css('display', 'none');
    $('#completed_list').css('display', 'none');
    $('#on_hold_list').css('display', 'none');
    $('#ptw_list').css('display', 'none');
    $('.mdc-toolbar__title').html('Dropped');
    window.scrollTo(0, 0);
});
$('#ptw_button').click(function() {
    $('#ptw_list').css('display', 'block');
    $('#watching_list').css('display', 'none');
    $('#completed_list').css('display', 'none');
    $('#on_hold_list').css('display', 'none');
    $('#dropped_list').css('display', 'none');
    $('.mdc-toolbar__title').html('Plan to Watch');
    window.scrollTo(0, 0);
});
</script>
<script>
(function() {
    setTimeout(function () {
      window.navBar = new mdc.tabs.MDCTabBar(document.querySelector('#anime_list_nav'));
    },200)
  })();
</script>
<script>
var request;
$("#mal_search").submit(function(event) {
    event.preventDefault();
    if (request) {
        request.abort();
    }
    var $form = $(this);
    var $inputs = $form.find("input, button");
    var serializedData = $form.serialize();
    $inputs.prop("disabled", true);
    request = $.ajax({
        url: "mal_search.php",
        type: "post",
        data: serializedData
    });
    request.done(function (response, textStatus, jqXHR){
        // Log a message to the console
        // console.log(response);
        $('#search_results').html(response);
        console.log(textStatus);
    });
    request.fail(function (jqXHR, textStatus, errorThrown){
        // Log the error to the console
        console.error(
            "The following error occurred: "+
            textStatus, errorThrown
        );
    });
    request.always(function () {
        // Reenable the inputs
        $inputs.prop("disabled", false);
    });
});
</script>
<script>
(function() {
    var pollId = 0;
    pollId = setInterval(function() {
        var pos = getComputedStyle(document.querySelector('.mdc-toolbar')).position;
        if (pos === 'fixed' || pos === 'relative') {
            init();
            clearInterval(pollId);
        }
    }, 250);
    function init() {
        var toolbar = mdc.toolbar.MDCToolbar.attachTo(document.querySelector('.mdc-toolbar'));
        toolbar.listen('MDCToolbar:change', function(evt) {
            var flexibleExpansionRatio = evt.detail.flexibleExpansionRatio;
        });
        toolbar.fixedAdjustElement = document.querySelector('.mdc-toolbar-fixed-adjust');
    }
})();
</script>
<script>
    (function() {
        var tfs = document.querySelectorAll('.mdc-textfield:not([data-demo-no-auto-js])');
        for (var i = 0, tf; tf = tfs[i]; i++) {
            mdc.textfield.MDCTextfield.attachTo(tf);
        }
    })();
</script>
</html>
</html>