<?php
session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../index.php');    
}
$id = $_GET['id'];

$searchResults = new SimpleXMLElement($_SESSION['searchResultsXml']);
foreach($searchResults->entry as $a) {
    if ($a->id == $id) {
        $animeInfo = $a;
        break;
    }
}
$start_date = formatDate($animeInfo->start_date);
$end_date = formatDate($animeInfo->end_date);
function formatDate($date) {
    $year = substr($date, 0, 4);
    $month = substr($date, 5, 2);
    $day = substr($date, 8, 2);
    if (substr($day, 0, 1) == '0') {
        $day = substr($day, 1, 1);
    }
    $formatted_date = '';
    if ($month == '01') {
        $formatted_date = 'January ' . $day . ', ' . $year;
    } else if ($month == '02') {
        $formatted_date = 'February ' . $day . ', ' . $year;
    } else if ($month == '03') {
        $formatted_date = 'March ' . $day . ', ' . $year;
    } else if ($month == '04') {
        $formatted_date = 'April ' . $day . ', ' . $year;
    } else if ($month == '05') {
        $formatted_date = 'May ' . $day . ', ' . $year;
    } else if ($month == '06') {
        $formatted_date = 'June ' . $day . ', ' . $year;
    } else if ($month == '07') {
        $formatted_date = 'July ' . $day . ', ' . $year;
    } else if ($month == '08') {
        $formatted_date = 'August ' . $day . ', ' . $year;
    } else if ($month == '09') {
        $formatted_date = 'September ' . $day . ', ' . $year;
    } else if ($month == '10') {
        $formatted_date = 'October ' . $day . ', ' . $year;
    } else if ($month == '11') {
        $formatted_date = 'November ' . $day . ', ' . $year;
    } else if ($month == '12') {
        $formatted_date = 'December ' . $day . ', ' . $year;
    } else {
        if ($year != '0000') {
            $formatted_date = $year;
        }
    }
    return $formatted_date;
}

$userListXml = file_get_contents('http://myanimelist.net/malappinfo.php?u=' . $_SESSION['user'] . '&status=all&type=anime');

$userList = new SimpleXMLElement($userListXml);
$userSaved = false;
foreach($userList->anime as $a) {
    if ($a->series_animedb_id == $id) {
        $userInfo = $a;
        $userSaved = true;
        break;
    }
}
?>
<!DOCTYPE html>
<html class="mdc-typography">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $animeInfo->title ?> Details | Elsie</title>
    <link rel="stylesheet" href="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="../styles/style.css">
    <link rel="stylesheet" href="../styles/theme.css">
    <link rel="stylesheet" href="../styles/anime.css">
    <link rel="stylesheet" href="../styles/search.css">
    <link rel="stylesheet" href="../styles/edit_user_details_dialog.css">
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

    <header id="main_toolbar" class="mdc-toolbar mdc-toolbar--fixed mdc-toolbar--waterfall">
      <div class="mdc-toolbar__row">
        <section class="mdc-toolbar__section mdc-toolbar__section--align-start">
            <a href="index.php" class="material-icons mdc-toolbar__menu-icon menu">arrow_back</a>
            <span class="mdc-toolbar__title">Anime Details</span>
        </section>
        <section id="search_section" class="mdc-toolbar__section">
            <i class="material-icons mdc-toolbar__menu-icon">search</i>
            <form id="mal_search">
                <div class="mdc-textfield" id="search_textfield" data-demo-no-auto-js="">
                    <input type="text" class="mdc-textfield__input" id="search_query" name="search_query" autocomplete="off" placeholder="Search" onkeyup="showResults()">
                  </div>
            </form>
            <i id="search_close" class="material-icons mdc-toolbar__menu-icon">close</i>
        </section>
        <section class="mdc-toolbar__section mdc-toolbar__section--align-end" role="toolbar">
            <i id="search_button_menu" class="material-icons mdc-toolbar__menu-icon">search</i>
            <i id="account_button" class="material-icons mdc-toolbar__menu-icon">account_circle</i>
            <img id="avatar" src="https://myanimelist.cdn-dena.com/images/userimages/<?= $_SESSION['id'] ?>.jpg">
            <div class="mdc-simple-menu mdc-simple-menu--open-from-top-right" style="position: absolute; top: 12px; right: 12px;" tabindex="-1" id="user_menu">
                <ul class="mdc-simple-menu__items mdc-list" role="menu" aria-hidden="true">
                    <a class="mdc-list-item" role="menuitem" tabindex="0" target="_blank" href="https://myanimelist.net/profile/<?= $_SESSION['user'] ?>">Profile</a>
                    <a class="mdc-list-item" role="menuitem" tabindex="0" href="logout.php">Logout</a>
                </ul>
            </div>
        </section>
      </div>
      <div role="progressbar" id="page_progress" class="mdc-linear-progress mdc-linear-progress--indeterminate mdc-linear-progress--accent">
      <div class="mdc-linear-progress__buffering-dots"></div>
      <div class="mdc-linear-progress__buffer"></div>
      <div class="mdc-linear-progress__bar mdc-linear-progress__primary-bar">
        <span class="mdc-linear-progress__bar-inner"></span>
      </div>
      <div class="mdc-linear-progress__bar mdc-linear-progress__secondary-bar">
        <span class="mdc-linear-progress__bar-inner"></span>
      </div>
    </div>
    </header>

    <div class="mdc-toolbar-fixed-adjust">

        <aside id="add_dialog" class="mdc-dialog" role="alertdialog" aria-labelledby="my-mdc-dialog-label" aria-describedby="my-mdc-dialog-description">
          <div class="mdc-dialog__surface">
            <header class="mdc-dialog__header">
              <h2 id="my-mdc-dialog-label" class="mdc-dialog__header__title">
                Add Anime?
              </h2>
            </header>
            <section id="my-mdc-dialog-description" class="mdc-dialog__body">
              Add <?= $animeInfo->title ?> to your list?
            </section>
            <footer class="mdc-dialog__footer">
              <button type="button" class="mdc-button mdc-dialog__footer__button mdc-dialog__footer__button--cancel">No</button>
              <button type="button" class="mdc-button mdc-dialog__footer__button mdc-dialog__footer__button--accept">Yes</button>
            </footer>
          </div>
          <div class="mdc-dialog__backdrop"></div>
        </aside>

        <?php include 'edit_dialog.php' ?>

    <main>
        <div id="anime_info_section" class="mdc-typography--body1" anime_id="<?= $animeInfo->id ?>">
            <div id="hero_header">
                <section>
                    <div id="anime_image">
                        <img id="image" src="<?= $animeInfo->image ?>">
                    </div>
                    <div id="anime_title_section">
                        <div id="anime_title">
                            <span class="mdc-typography--title"><?= $animeInfo->title ?></span>
                        </div>
                        <div id="hashtag"></div>
                        <div id="anime_title_info">
                            <span class="mdc-typography--caption">
                                <?php 
                                $seasonNum = substr($animeInfo->start_date, 5, 2);
                                $year = substr($animeInfo->start_date, 0, 4);
                                if ($seasonNum == '01' || $seasonNum == '02' || $seasonNum == '03') {
                                    echo 'Winter ' . $year;
                                } else if ($seasonNum == '04' || $seasonNum == '05' || $seasonNum == '06') {
                                    echo 'Spring ' . $year;
                                } else if ($seasonNum == '07' || $seasonNum == '08' || $seasonNum == '09') {
                                    echo 'Summer ' . $year;
                                } else if ($seasonNum == '10' || $seasonNum == '11' || $seasonNum == '12') {
                                    echo 'Fall ' . $year;
                                } else if ($year != '0000') {
                                    echo $year;
                                    }
                                 ?>
                            </span>
                        </div>
                        <div id="next_episode">
                        </div>
                        <div id="external_links">
                            <button id="sites_toggle" class="mdc-button icon_button">
                                <i class="material-icons">public</i>
                            </button>
                            <div class="mdc-simple-menu mdc-simple-menu--open-from-top-right" style="position: absolute; top: 0; right: 0;" tabindex="-1" id="external_links_menu">
                                <ul class="mdc-simple-menu__items mdc-list" role="menu" aria-hidden="true">
                                    <a class="mdc-list-item" role="menuitem" tabindex="0" target="_blank" href="https://myanimelist.net/anime/<?= $id ?>">MyAnimeList Page</a>
                                </ul>
                            </div>
                        </div>

                        <?php if($userSaved): ?>
                            <button id="anime_fab" class="mdc-fab material-icons">
                                <span class="mdc-fab__icon">edit</span>
                            </button>
                        <?php else: ?>
                            <button id="anime_fab" class="mdc-fab material-icons">
                                <span class="mdc-fab__icon">add</span>
                            </button>
                        <?php endif;?>
                    </div>
                </section>
            </div>
            <div id="anime_info_body">

                <section id="circle_row">
                    <div class="description_circle">
                        <span class="mdc-typography--caption">Episodes</span>
                        <span class="mdc-typography--body2"><?= $animeInfo->episodes ?></span>
                    </div>
                    <div class="description_circle">
                        <span class="mdc-typography--caption">Rating</span>
                        <span class="mdc-typography--body2">
                            <?php if($animeInfo->status != 'Not yet aired') {
                                echo $animeInfo->score;
                            } else {
                                echo 'N/A';
                            }  ?></span>
                    </div>
                </section>
                
                <hr class="mdc-list-divider">

                <section id="anime_description">
                    <div id="description">
                        <?php if (strlen($animeInfo->synopsis) == 0) {
                            echo 'No synopsis information has been added for this title.';
                        } else {
                            echo preg_replace('#\[i\](.+)\[\/i\]#iUs', '<span class="mdc-typography--body2">$1</span>', $animeInfo->synopsis);
                        } ?>        
                        </div>
                </section>

                <hr class="mdc-list-divider">

                <section id="episode_section">
                    <div class="mdc-grid-list mdc-grid-list--twoline-caption">
                      <ul class="mdc-grid-list__tiles">
                      </ul>
                    </div>                
                </section>

                <hr class="mdc-list-divider" style="display: none;">

                <section id="trailer_section">
                    <div class="mdc-grid-list">
                      <ul class="mdc-grid-list__tiles">
                        <a class="mdc-grid-tile" target="_blank" href="">
                          <div class="mdc-grid-tile__primary">
                            <img class="mdc-grid-tile__primary-content" src="" />
                          </div>
                          <span class="mdc-grid-tile__secondary">
                            <span class="mdc-grid-tile__title">Trailer</span>
                          </span>
                        </a>
                      </ul>
                    </div>                
                </section>

                <hr class="mdc-list-divider" style="display: none;">

                <section id="extra_detail_section">
                    <div id="extra_details">
                        <?php if(strlen($animeInfo->synonyms) > 0): ?>   
                            <div id="anime_title_synonyms">
                                <span class="mdc-typography--body2">Alternative Titles</span><br>
                                <span class="mdc-typography--body1"><?= $animeInfo->synonyms ?></span>
                            </div>
                        <?php endif; ?>
                        <div id="type">
                            <span class="mdc-typography--body2">Type</span><br>
                            <span class="mdc-typography--body1"><?= $animeInfo->type ?></span>
                        </div>
                        <div id="status">
                            <span class="mdc-typography--body2">Status</span><br>
                            <span class="mdc-typography--body1"><?= $animeInfo->status ?></span> 
                        </div>
                        <?php if($animeInfo->start_date != '0000-00-00'): ?>  
                            <div id="start_date">
                                <span class="mdc-typography--body2">
                                    <?php if ($animeInfo->episodes != '1') { echo 'Start Date'; } 
                                    else { echo 'Release Date'; } ?>
                                </span><br>
                                <span class="mdc-typography--body1"><?= $start_date ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if($animeInfo->end_date != '0000-00-00' && $animeInfo->episodes != '1'): ?>   
                            <div id="end_date">
                                <span class="mdc-typography--body2">End Date</span><br>
                                <span class="mdc-typography--body1"><?= $end_date ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </div>
        </div>

        <div id="search_background"></div>

        <div id="search_sheet" class="mdc-typography--body1">
            <div id="search_body">
                <div id="search_results">
                    <ul id="search_results_list" class="mdc-list mdc-list--two-line mdc-list--dense">
                        <!-- handled through mal_search.php -->
                    </ul>
                </div>
            </div>
        </div>

    </main>        

    </div>

</body>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.js"></script>
<script src="../scripts/search.js"></script>
<script src="../scripts/toolbar.js"></script>
<script src="../scripts/textfield.js"></script>
<script src="../scripts/user_menu.js"></script>
<script>
var query = `
query ($idMal: Int) {
  Media (idMal: $idMal, type: ANIME) {
    id
    hashtag
    bannerImage
    trailer {
      id
    }
    nextAiringEpisode {
      airingAt
      episode
    }
    streamingEpisodes {
      title
      thumbnail
      url
      site
    }
    externalLinks {
      url
      site
    }
  }
}
`;
var id = $('#anime_info_section').attr('anime_id')
var variables = {
    idMal: id
};

var url = 'https://graphql.anilist.co',
    options = {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            query: query,
            variables: variables
        })
    };
fetch(url, options).then(handleResponse)
                   .then(handleData)
                   .catch(handleError);

function handleResponse(response) {
    return response.json().then(function (json) {
        return response.ok ? json : Promise.reject(json);
    });
}

function handleData(data) {
    var bgUrl = data['data']['Media']['bannerImage'];
    var hashtag = data['data']['Media']['hashtag'];
    var links = data['data']['Media']['externalLinks'];
    var trailer = data['data']['Media']['trailer'];
    var nextEp = data['data']['Media']['nextAiringEpisode'];
    var streamingEps = data['data']['Media']['streamingEpisodes'];
    if (bgUrl != null) {
        $('#hero_header').css('background-image', 'url(' + bgUrl + ')');
        $('#hero_header section').css('background-color', 'var(--mdc-theme-text-secondary-on-background)');
        $('.mdc-toolbar').removeClass('mdc-toolbar--waterfall');
    } 
    if (trailer != null) {
        $('#trailer_section').css('display', 'block');
        $('#trailer_section').next().css('display', 'block');
        var thumbUrl = 'https://img.youtube.com/vi/' + trailer['id'] + '/mqdefault.jpg';
        $('#trailer_section .mdc-grid-tile__primary-content').attr('src', thumbUrl);
        $('#trailer_section .mdc-grid-tile').attr('href', 'https://www.youtube.com/watch?v=' + trailer['id']);
    }
    if (nextEp != null) {
        var airingAt = moment.unix(nextEp['airingAt']).format('MMM. D [at] h:mm a');
        // var timeUntilAiring = moment.unix(nextEp['airingAt']).fromNow();
        $('#next_episode').html('<span class="mdc-typography--caption">Episode ' + nextEp['episode'] + ': ' +  airingAt + '</span>');
    }
    if (links != null) {
        for(var i = 0; i < links.length; i++) {
            $('#external_links_menu ul').append('<a class="mdc-list-item" role="menuitem" tabindex="0" target="_blank" href="' + links[i]['url'] + '">' + links[i]['site'] + '</a>');
        }
    }
    if (hashtag != null) {
        var hashtags = hashtag.split('#');
        for (var i = 1; i < hashtags.length; i++) {
            $('#hashtag').append('<a class="mdc-typography--body2" target="_blank" href="https://twitter.com/search?q=%23' + hashtags[i] + '">#' + hashtags[i] + '</a>');
        }
    }
    if (streamingEps.length > 0) {
        $('#episode_section').css('display', 'block');
        $('#episode_section').next().css('display', 'block');
        for (var i = 0; i < streamingEps.length; i++) {
            var title = streamingEps[i]['title'].split(" - ");
             $('#episode_section .mdc-grid-list__tiles').append('<a class="mdc-grid-tile" target="_blank" href="' + streamingEps[i]['url'] + '"><div class="mdc-grid-tile__primary"><img class="mdc-grid-tile__primary-content" src="' + streamingEps[i]['thumbnail'] + '"/></div><span class="mdc-grid-tile__secondary"><span class="mdc-grid-tile__title">' + title.slice(1).join(" - ") + '</span><span class="mdc-grid-tile__support-text">' + title[0] + '</span></span></a>');
        }
       
    }
    $('.mdc-toolbar-fixed-adjust').css('display', 'block');
    $('#page_progress').css('display', 'none');
    // if ($('#hero_header img').attr('src').length > 0) {
    //     $('#hero_header img').on('load', function() {
    //         $('.mdc-toolbar-fixed-adjust').css('display', 'block');
    //         $('#page_progress').css('display', 'none');
    //     });
    // } else {
    //     $('.mdc-toolbar-fixed-adjust').css('display', 'block');
    // }
    // var json = JSON.parse(data);
}

function handleError(error) {
    // alert('Error, check console');
    console.error(error);
}

// window.onload = function() {
</script>
<script>
document.querySelector('#anime_fab').addEventListener('click', function (evt) {
    // $('#anime_fab').fadeOut('fast');
    if ($('#anime_fab span').text() == 'edit') {
        var dialog = new mdc.dialog.MDCDialog(document.querySelector('#edit_dialog'));
    } else {
        var dialog = new mdc.dialog.MDCDialog(document.querySelector('#add_dialog'));
    }
    dialog.lastFocusedTarget = evt.target;
    dialog.show();     
})
</script>
<script>
    var menuEl = document.querySelector('#external_links_menu');
      var menu = new mdc.menu.MDCSimpleMenu(menuEl);
      var toggle = document.querySelector('#sites_toggle');
      toggle.addEventListener('click', function() {
        menu.open = !menu.open;
      });
</script>
</html>