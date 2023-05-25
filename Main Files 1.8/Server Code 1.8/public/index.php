<?php

//show Errors
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set("pcre.jit", "0");
include_once("../sys/core/init.inc.php");

$page_id = '';

if (!empty($_GET)) {

    if (!isset($_GET['q'])) {

        include_once("../html/main.inc.php");
        exit;
    }

    $request = htmlentities($_GET['q'], ENT_QUOTES);
    $request = helper::escapeText($request);
    $request = explode('/', trim($request, '/'));

    $cnt = count($request);

	switch ($cnt) {

		case 0: {

			include_once("../html/main.inc.php");
			exit;
		}

		case 1: {

			if (file_exists("../html/page.".$request[0].".inc.php")) {

				include_once("../html/page.".$request[0].".inc.php");
				exit;

			} else {

				include_once("../html/error.inc.php");
				exit;
			}
		}

		case 2: {

			if (file_exists( "../html/".$request[0]."/page.".$request[1].".inc.php")) {

				include_once("../html/" . $request[0] . "/page." . $request[1] . ".inc.php");
				exit;

			} else if (file_exists( "../html/".$request[0]."/cron.".$request[1].".inc.php")) {

                include_once("../html/" . $request[0] . "/cron." . $request[1] . ".inc.php");
                exit;

            } else if (file_exists( "../html/".$request[0]."/app.".$request[1].".inc.php")) {

                include_once("../html/" . $request[0] . "/app." . $request[1] . ".inc.php");
                exit;

            } else if (file_exists("../html/app/".$request[1].".php")) {

                include_once("../html/app/" . $request[1] . ".php");
                exit;

            } else {
                    if ($request[0] == "feed" || $request[0] == "feedVip" || $request[0] == "question") {
                        include_once("../html/main.inc.php");
                    }else{
                        include_once("../html/error.inc.php");
                    }
                    exit;
            }
		}

        case 3: {

            switch ($request[0]) {

                case 'api': {
                    if (file_exists("../app/".$request[1]."/method/".$request[2].".inc.php")) {

                        //include_once("../sys/config/api.inc.php");
                        header("Content-type: application/json; charset=utf-8");

                        include_once("../app/".$request[1]."/method/".$request[2].".inc.php");
                        exit;

                    } else if (file_exists("../html/".$request[0]."/".$request[1]."/page.".$request[2].".inc.php")) {

                        include_once("../html/".$request[0]."/".$request[1]."/page.".$request[2].".inc.php");
                        exit;

                    } else {

                        include_once("../html/error.inc.php");
                        exit;
                    }

                    break;
                }

                default: {

                        if ( file_exists("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php") ) {

                            include_once("../html/".$request[0]."/".$request[1]."/".$request[2]."/page.".$request[3].".inc.php");
                            exit;

                        } else {

                            include_once("../html/error.inc.php");
                            exit;
                        }
                    

                    break;
                }
            }
        }

		default: {

			include_once("../html/error.inc.php");
			exit;
		}
	}

} else {

	$request = array();
	include_once("../html/main.inc.php");
	exit;
}
