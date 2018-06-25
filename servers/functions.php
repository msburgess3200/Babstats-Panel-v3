<?php
ob_start();
/*
 * Copyright (c) 2003, Tomas Stucinskas a.k.a Baboon
 * All rights reserved.
 *
 * Redistribution and use with or without modification, are
 * permitted provided that the following conditions are met:
 *
 * Redistributions must retain the above copyright notice.
 * File licence.txt must not be removed from the package.
 *
 * Author        : Tomas Stucinskas a.k.a Baboon
 * E-mail        : baboon@ai-hq.com
 * 
 * Finalized     : 4th May 2005
 * Modified by   : Peter Jones a.k.a »TÐÖ« Ãzràél
 * E-mail        : azrael@tdosquad.co.uk
 * Website       : http://www.tdosquad.co.uk
 */

// if($_GET["version"]) {
	// echo 'functions.php 1.0.0';
	// die();
// }

function GetServer($id_code) {
  
  $id_code = escape_string($id_code);
  $query = query("SELECT * FROM `".$bmt_pre."servers` WHERE serverid='".$id_code."'");
  if(num_rows($query) == 0) {
    return -999;
  }
  $server = fetch_array($query);
  return $server["id"];
}

function GetMap($map_name, $game_type, $ssid) {
  if(!isset($map_name) || $map_name == "") return -999;
  $query = query("SELECT * FROM `".$bmt_pre."maps` WHERE name='".$map_name."' AND game_type='".$game_type."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."maps VALUES (NULL, '".$map_name."', '', '', '', 0, 0, '".$game_type."', 0)");
	query("UPDATE ".$bmt_pre."serverstats SET 
	             maps       = maps   + 1              
               WHERE id='".$ssid."'");
  }
  $query = query("SELECT * FROM ".$bmt_pre."maps WHERE name='".$map_name."' AND game_type='".$game_type."'");
  $map = fetch_array($query);
  return $map["id"];
}

function GetMonthMapRecord($map_id, $record_id) {
  if($map_id == -999 || $record_id == -999) return -999;
  $query = query("SELECT * FROM ".$bmt_pre."m_mapstats WHERE map='".$map_id."' AND record='".$record_id."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."m_mapstats VALUES (NULL, '".$record_id."', '".$map_id."', 0, 0, 0, 0)");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."m_mapstats WHERE map='".$map_id."' AND record='".$record_id."'");
  $map_record = fetch_array($query);
  return $map_record["id"];
}

function GetMapRecord($map_id, $record_id) {
  if($map_id == -999 || $record_id == -999) return -999;
  $query = query("SELECT id FROM ".$bmt_pre."mapstats WHERE record='".$record_id."' AND map='".$map_id."' ");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."mapstats VALUES (NULL, '".$record_id."', '".$map_id."', 0, 0, 0, 0)");
  }  
  $query = query("SELECT id FROM ".$bmt_pre."mapstats WHERE map='".$map_id."' AND record='".$record_id."'");
  $map_record = fetch_array($query);
  return $map_record["id"];
}

function GetPlayer($player_name) {
  if(!isset($player_name) || $player_name == "") return -999;
  $query = query("SELECT * FROM ".$bmt_pre."players WHERE name='".$player_name."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."players VALUES (NULL, '".$player_name."', '-1', 0, 0, 0, 0, 0, 0)");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."players WHERE name='".$player_name."'");
  $player = fetch_array($query);
  return $player["id"];
}

function GetNcVal($value) {
	if($value != '1') die ("Uploading Failed, Incorrect Data!");
}

function GetPlayerRecord($player_id, $game_type, $server_id) {
  if($player_id == -999) return -999;
  $query = query("SELECT * FROM ".$bmt_pre."stats WHERE player='".$player_id."' AND server='".$server_id."' AND game_type='".$game_type."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."stats VALUES (NULL, '".$player_id."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '".$server_id."', '".$game_type."', '0000-00-00 00:00:00')");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."stats WHERE player='".$player_id."' AND server='".$server_id."' AND game_type='".$game_type."'");
  $record = fetch_array($query);
  return $record["id"];
}

function GetGame($map_id, $winner, $server, $game_type, $date) {
  query("INSERT INTO ".$bmt_pre."games VALUES (NULL, '".$map_id."', '".$winner."', '".$server."', '".$game_type."', '".$date."')");  
  $query = query("SELECT * FROM ".$bmt_pre."games WHERE map_id = '".$map_id."' AND server = '".$server."' 
                                               AND game_type = '".$game_type."' AND date_played = '".$date."'");
  $record = fetch_array($query);
  return $record["id"];
}

function GetPlayerGame($player_id, $game_id, $team) {
  $query = query("SELECT * FROM ".$bmt_pre."playergames WHERE player = '".$player_id."' AND game_id = '".$game_id."' 
                                                     AND team = '".$team."'");
  if(num_rows($query) == 0) {
  	$record["id"] = '-1';
  } else {
  	$record = fetch_array($query);
  }
  return $record["id"];
}

function storePlayerIp($player_id, $ip, $date) {
  $query = query("SELECT * FROM ".$bmt_pre."playerips WHERE player = '".$player_id."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."playerips VALUES (NULL, '".$player_id."', '".$ip."', '".$date."')");
  } else {
  	query("UPDATE ".$bmt_pre."playerips SET last_recorded = '".$date."'");
  }
}

function GetMonthPlayerRecord($player_id, $game_type, $server_id, $mDate) {
  $exDate = explode("-", $mDate);
  if($exDate[1] < '10') $exDate[1] = '0'.$exDate[1];
  $subDate = $exDate[0]."-".$exDate[1];
  if($player_id == -999) return -999;
  $query = query("SELECT * FROM ".$bmt_pre."m_stats WHERE player='".$player_id."' AND server='".$server_id."' AND game_type='".$game_type."' AND 
                    SUBSTRING(last_played, 1, 7) = '".$subDate."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."m_stats VALUES (NULL, '".$player_id."', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, '".$server_id."', '".$game_type."', '".$mDate."')");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."m_stats WHERE player='".$player_id."' AND server='".$server_id."' AND game_type='".$game_type."' AND 
                    SUBSTRING(last_played, 1, 7) = '".$subDate."'");
  $record = fetch_array($query);
  return $record["id"];
}


function GetPlayerAlias($player_name) {
  $query = query("SELECT * FROM ".$bmt_pre."aliases WHERE from_name='".$player_name."'");
  if(num_rows($query) != 0) {
    $alias = fetch_array($query);
    return $alias["to_name"];
  }
  return $player_name;
}

function GetServerStats($serverid, $game_type) {
  if(!isset($serverid) || $serverid == "") return -999;
  $query = query("SELECT * FROM ".$bmt_pre."serverstats WHERE serverid='".$serverid."' AND game_type='".$game_type."'");
  
  if(num_rows($query) == 0) {
	query("INSERT INTO ".$bmt_pre."serverstats VALUES (NULL, '".$serverid."', '".$game_type."', '', '', '')");
  }
  $query = query("SELECT * FROM ".$bmt_pre."serverstats WHERE serverid='".$serverid."' AND game_type='".$game_type."'");
  $serverstats = fetch_array($query);
  return $serverstats["id"];
}


function GetServerHistory($serverid, $name, $date) {
  if(!isset($serverid) || $serverid == "") return -999;
  $query = query("SELECT * FROM ".$bmt_pre."serverhistory WHERE serverid='".$serverid."' AND name = '".$name."' AND date='".$date."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."serverhistory VALUES (NULL, '".$serverid."', 0, '".$name."', '".$date."')");
  }
  $query = query("SELECT * FROM ".$bmt_pre."serverhistory WHERE serverid='".$serverid."' AND name = '".$name."' AND date='".$date."'");
  $serverhistory = fetch_array($query);
  return $serverhistory["name"];
}

function GetWeapon($weapon_name) {
  if(!isset($weapon_name) || $weapon_name == "") return -999;
  $query = query("SELECT * FROM ".$bmt_pre."weapons WHERE name='".$weapon_name."'");
  if(num_rows($query) == 0) {
   query("INSERT INTO ".$bmt_pre."weapons VALUES (NULL, '".$weapon_name."')");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."weapons WHERE name='".$weapon_name."'");
  $weapon = fetch_array($query);
  return $weapon["id"];
}

function GetWeaponRecord($weapon_id, $record_id) {
  if($weapon_id == -999 || $record_id == -999) return -999;
  $query = query("SELECT * FROM ".$bmt_pre."weaponstats WHERE record='".$record_id."' AND weapon='".$weapon_id."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."weaponstats VALUES (NULL, '".$record_id."', '".$weapon_id."', 0, 0, 0)");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."weaponstats WHERE record='".$record_id."' AND weapon='".$weapon_id."'");
  $record = fetch_array($query);
  return $record["id"];
}

function GetMonthWeaponRecord($weapon_id, $record_id) {
  if($weapon_id == -999 || $record_id == -999) return -999;
  $query = query("SELECT * FROM ".$bmt_pre."m_weaponstats WHERE record='".$record_id."' AND weapon='".$weapon_id."'");
  if(num_rows($query) == 0) {
    query("INSERT INTO ".$bmt_pre."m_weaponstats VALUES (NULL, '".$record_id."', '".$weapon_id."', 0, 0, 0)");
  }  
  $query = query("SELECT * FROM ".$bmt_pre."m_weaponstats WHERE record='".$record_id."' AND weapon='".$weapon_id."'");
  $record = fetch_array($query);
  return $record["id"];
}

function ImportStats($data) {

  global $bmt_pre, $dow, $games, $today, $wpn_points, $points, $percenttowin, $gametypes, $wpn_name, $bmt;
  
  $log = "";

  $data_lines = explode("\n", $data);
  $line_count = count($data_lines);

  $status = "blank";
  
  $server_id        = 0;
  $player_id        = 0;
  $player_record_id = 0; 
  $weapon_id        = 0;
  $weapon_record_id = 0; 
  $map_id           = 0;
  $map_record_id    = 0;
   
  $game        = array();
  $player      = array();
  $weapon      = array();
  
  $game_data = array(
    0 => "timer"     ,
    1 => "date"      ,
    2 => "gametype"  ,
    3 => "dedicated" ,
    4 => "servername",
    5 => "mapname"   ,
    6 => "maxplayers",
    7 => "numplayers",
    8 => "winner"    , 
	9 => "mod"
  );                             

  $player_data = array(
    0  => "suicides"        ,
    1  => "murders"         ,
    2  => "kills"           ,
    3  => "deaths"          ,
    4  => "zonetime"        ,
    5  => "flags"           ,
    6  => "flagsaves"       ,
    7  => "targets"         ,
    8  => "revives"         ,
    9  => "medsaves"        ,
    10 => "pspattempts"     ,
    11 => "psptakeovers"    ,
    12 => "flagcarrierkills",
    13 => "doublekills"     ,
    14 => "headshots"       ,
    15 => "knifings"        ,
    16 => "sniperkills"     ,
    17 => "tkothattackkills",
    18 => "tkothdefendkills",
    19 => "sdaddefendkills" ,
    20 => "sdadpolicekills" ,
    21 => "sdadattackkills" ,
    22 => "sdadsecurekills" ,
    23 => "shotsperkill"    ,
    24 => "experience"      ,
    25 => "team"            ,
    26 => "playedtillend"   ,
    27 => "timer"
  );
                              
  $weapon_data = array(
    0 => "code",
    1 => "timer",
    2 => "kills",
    3 => "shots"
  );                             

  $skip        = false;
  $skip_to     = "";
  
  //for($i = 0, $max = $line_count; $i < $max; $i++) {
  for($i = 0; $i < $line_count; $i++) {

    $line = $data_lines[$i];
    $tag  = explode(" ", trim($line));
    $tag  = $tag[0];
    
    $offset = 0;
    
    switch($tag) {
      case "ServerID":    {$status = "server_id";   $offset = 8; } break;
      case "Game":        {$status = "game";        $offset = 5; } break;
      case "Player":      {$status = "player";      $offset = 8; } break;
      case "PlayerStats": {$status = "player_stats";$offset = 14;} break;
      case "Weapon":      {$status = "weapon";      $offset = 9; } break;
      default:            {$status = "blank";       $offset = 0; }
    }
    
    if($skip && $status != $skip_to) continue;
    
    $skip    = false;
    $skip_to = "";
    
    $line = trim(substr($line, $offset));

    if($status == "blank") {}
    
    if($status == "server_id") {
      $id_code = $line;
      $server_id = GetServer($id_code);
      if($server_id == -999) {
        $log .= "Invalid server id code ($id_code)";
        $skip    = true;
        $skip_to = "server_id"; 
        continue;
      }
    }
    
    if($status == "game") {
      $tmp = array();
      $tmp = explode("__&__", $line);
      foreach($tmp as $field => $value) {
        $game[$game_data[$field]] = $tmp[$field];
      }
	  $game["timer"] = str_replace("Game ", "", $game["timer"]);
	  $query = query("SELECT * FROM $log_table WHERE server='".$server_id."' AND datetime='".$game["date"]."'");
        if(num_rows($query) != 0) {
          die("Already imported - ".$game["date"]);
          $skip    = true;
          $skip_to = "server_id";
          continue;
        }
       
	  if(!$bmt) $bmt = $_GET["bmt"];
	   
      if(!isset($gametypes[$game["gametype"]])) {
        $log .= "Unknown gametype (Code ".$game["gametype"].")";
        $skip    = true;
        $skip_to = "server_id";
        continue;
      }
	  $type             = $game["gametype"];
      $game["mapname"]  = base64_encode(str_replace("??", "", $game["mapname"]));
      $game["gametype"] = $gametypes[$game["gametype"]];
	  $serverid         = GetServerStats($server_id, $game["gametype"]);
	  $map_id           = GetMap($game["mapname"], $game["gametype"], $serverid);
	  
      query("UPDATE $maps_table SET 
                 time        = time    + '".$game["timer"]."' ,
                 hosted      = hosted  + 1                    ,
                 last_played = '".$game["date"]."'             
               WHERE id='".$map_id."'");
			   
	  query("UPDATE $serverstats_table SET 
	             games       = games   + 1                    ,
                 time        = time    + '".$game["timer"]."'              
               WHERE id='".$serverid."'");
			   
	  $game_id = GetGame($map_id, $game["winner"], $server_id, $game["gametype"], $game["date"]);
    }
    
    if($status == "player") {
      $plyrStr = explode("__&__", $line);
	  $player["name"] = $plyrStr[0];
	  $player["ip"]   = $plyrStr[1];
	  $pn = array($player["name"]);
	  	foreach($pn as $value) {  
		  $pnm = base64_encode($value);
		  $name = query("SELECT * FROM $players_table WHERE name = '$pnm'");
          while($n_row = fetch_array($name)) {
            $id = $n_row["id"];
            $award_query = query("SELECT * FROM $awards_table WHERE type = 'A'");
            $stats_query = query("SELECT * FROM $stats_table WHERE $stats_table.player='$id'");
            $award_stats = array();
            $stats_row   = array();
            while($stats_row = fetch_array($stats_query)) {
              foreach($stats_row as $field => $value) {
                if($field * 1 == 0 && $field != "0") {
                  $stats_field = "";
                  switch($field) {
                    case "kills":        $stats_field = "kills";           break;
                    case "deaths":       $stats_field = "deaths";          break;
                    case "murders":      $stats_field = "murders";         break;
                    case "suicides":     $stats_field = "suicides";        break;
                    case "knifings":     $stats_field = "knifings";        break;
                    case "headshots":    $stats_field = "headshots";       break;
                    case "medic_saves":  $stats_field = "medic_saves";     break;
                    case "revives":      $stats_field = "revives";         break;
                    case "pspattempts":  $stats_field = "psp_attempts";    break;
                    case "psptakeovers": $stats_field = "psp_takeovers";   break;
                    case "doublekills":  $stats_field = "double_kills";    break;
                    case "time":         $stats_field = "time_played";     break;
                    case "games":        $stats_field = "games_completed"; break;
                    case "wins":         $stats_field = "team_games_won";  break;
                  }
                  if($stats_row["game_type"] == "Team King of the Hill") {
                    switch($field) {
                      case "score_1": $stats_field = "zone_time";               break;
                      case "score_2": $stats_field = "tkoth_zone_attack_kills"; break;
                      case "score_3": $stats_field = "tkoth_zone_defend_kills"; break;
                    }
                  }
                  if($stats_row["game_type"] == "Capture the Flag" || $stats_row["game_type"] == "Flagball") {
                    switch($field) {
                      case "score_1": $stats_field = "flags_captured";      break;
                      case "score_2": $stats_field = "flags_saved";         break;
                      case "score_3": $stats_field = "flag_runners_killed"; break;
                    }
                  }
                  if($stats_row["game_type"] == "Search and Destroy" || $stats_row["game_type"] == "Attack and Defend") {
                    switch($field) {
                      case "score_1": $stats_field = "targets_destroyed";  break;
                      case "score_2": $stats_field = "sd_ad_attack_kills"; break;
                      case "score_3": $stats_field = "sd_ad_defend_kills"; break;
                    }
                  }
                  if($stats_field != "") {
                    if(isset($award_stats[$stats_field])) {
                      $award_stats[$stats_field] += $value;
                    } else {
                      $award_stats[$stats_field] = $value;
                    }
                  }
                }
              }
            }

            $awards = array();
            $award  = array();
            $count  = 0;
            while($award_row = fetch_array($award_query)) {
              if(isset($award_stats[$award_row["field"]])) {
                if($award_stats[$award_row["field"]] >= $award_row["value"]) {
		          $awrn = array($award_row["name"]);
		          foreach($awrn as $awval) {
		            $ga_sql = mysql_query("SELECT * FROM $playerawards_table WHERE player = '$id' AND award = '".addslashes($awval)."'");
			        $chkrow = mysql_num_rows($ga_sql);
			        if(!($chkrow)) {
			          $ga_row = fetch_array($ga_sql);
		              $ga_query = query("INSERT INTO $playerawards_table VALUES (NULL, '$id', '".addslashes($award_row["name"])."', '".$game["date"]."');");
					  $gt_sql = query("SELECT COUNT(*) AS num FROM $playerawards_table WHERE player = '$id' GROUP BY player");
					  $gt_row = fetch_array($gt_sql);
					  $up_query = query("UPDATE $players_table SET awards = awards + 1 WHERE id = '$id'");
		            } else {
					}
		          }
                }
              }
            }
			$wpnstats_query = query("SELECT $stats_table.id, $stats_table.player AS player, $weaponstats_table.id, 
                           $weaponstats_table.record, $weapons_table.name, $weaponstats_table.weapon AS weapon, 
						   SUM( $weaponstats_table.kills ) AS kills,
						   SUM( $weaponstats_table.kills ) *100 / SUM( $weaponstats_table.shots ) AS accuracy
                           FROM $stats_table, $weaponstats_table, $weapons_table
                           WHERE $weaponstats_table.record = $stats_table.id
                           AND $stats_table.player = '$id'
                           AND $weapons_table.id = $weaponstats_table.weapon
                           GROUP BY player, weapon"); 
						   
            $wpnaward_query = query("SELECT * FROM $awards_table WHERE type = 'W'"); 
			$wpnaward_stats = array();
            $wpnstats_row   = array();
            while($wpnstats_row = fetch_array($wpnstats_query)) {
              foreach($wpnstats_row as $field => $value) {
                if($field * 1 == 0 && $field != "0") {
                $wpnstats_field = "";
                if($wpnstats_row["name"] == "CAR15") {
                  switch($field) {
                    case "kills": $wpnstats_field = "car15_kills";      break;
			        case "accuracy": $wpnstats_field = "car15_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "G36") {
                  switch($field) {
                    case "kills": $wpnstats_field = "g36_kills";      break;
			        case "accuracy": $wpnstats_field = "g36_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "G3") {
                  switch($field) {
                    case "kills": $wpnstats_field = "g3_kills";      break;
			        case "accuracy": $wpnstats_field = "g3_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M60") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m60_kills";      break;
			        case "accuracy": $wpnstats_field = "m60_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "CAR15/M203") {
                  switch($field) {
                    case "kills": $wpnstats_field = "car15203_kills";      break;
			        case "accuracy": $wpnstats_field = "car15203_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M16") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m16_kills";      break;
			        case "accuracy": $wpnstats_field = "m16_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M16/M203") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m16203_kills";      break;
			        case "accuracy": $wpnstats_field = "m16203_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "MP5") {
                  switch($field) {
                    case "kills": $wpnstats_field = "mp5_kills";      break;
			        case "accuracy": $wpnstats_field = "mp5_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M249 SAW") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m249saw_kills";      break;
			        case "accuracy": $wpnstats_field = "m249saw_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M240") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m240_kills";      break;
			        case "accuracy": $wpnstats_field = "m240_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "CAR15/M203 - Grenade") {
                  switch($field) {
                    case "kills": $wpnstats_field = "car15203_grenade_kills";      break;
			        case "accuracy": $wpnstats_field = "car15203_grenade_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M16/M203 - Grenade") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m16203_grenade_kills";      break;
			        case "accuracy": $wpnstats_field = "m16203_grenade_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "Colt .45") {
                  switch($field) {
                    case "kills": $wpnstats_field = "colt_kills";      break;
			        case "accuracy": $wpnstats_field = "colt_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M9 Beretta") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m9_kills";      break;
			        case "accuracy": $wpnstats_field = "m9_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M21") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m21_kills";      break;
			        case "accuracy": $wpnstats_field = "m21_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "PSG1") {
                  switch($field) {
                    case "kills": $wpnstats_field = "psg1_kills";      break;
			        case "accuracy": $wpnstats_field = "psg1_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "M24") {
                  switch($field) {
                    case "kills": $wpnstats_field = "m24_kills";      break;
			        case "accuracy": $wpnstats_field = "m24_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "MCRT .300 Tactical") {
                  switch($field) {
                    case "kills": $wpnstats_field = "mcrt_kills";      break;
			        case "accuracy": $wpnstats_field = "mcrt_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "Barrett .50 Cal") {
                  switch($field) {
                    case "kills": $wpnstats_field = "barrett_kills";      break;
			        case "accuracy": $wpnstats_field = "barrett_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "Remington Shotgun") {
                  switch($field) {
                    case "kills": $wpnstats_field = "shotgun_kills";      break;
			        case "accuracy": $wpnstats_field = "shotgun_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "Frag Grenade") {
                  switch($field) {
                    case "kills": $wpnstats_field = "grenade_kills";      break;
			        case "accuracy": $wpnstats_field = "grenade_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "AT4") {
                  switch($field) {
                    case "kills": $wpnstats_field = "at4_kills";      break;
			        case "accuracy": $wpnstats_field = "at4_accuracy";      break;
                  }
                }
		        if($wpnstats_row["name"] == "Claymore") {
                  switch($field) {
                    case "kills": $wpnstats_field = "claymore_kills";      break;
			        case "accuracy": $wpnstats_field = "claymore_accuracy";      break;
                  }
                }
                if($wpnstats_field != "") {
                  if(isset($wpnaward_stats[$wpnstats_field])) {
                    $wpnaward_stats[$wpnstats_field] += $value;
                  } else {
                    $wpnaward_stats[$wpnstats_field] = $value;
                  }
                }
              }
            }
          }
          $wpnawards = array();
          $wpnaward  = array();
          $count  = 0;
          while($wpnaward_row = fetch_array($wpnaward_query)) {
            if(isset($wpnaward_stats[$wpnaward_row["field"]])) {
              if($wpnaward_stats[$wpnaward_row["field"]] >= $wpnaward_row["value"]) {
                $wawrn = array($wpnaward_row["name"]);
		        foreach($wawrn as $wawval) {
		          $ga_sql = mysql_query("SELECT * FROM $playerawards_table WHERE player = '$id' AND award = '".addslashes($wawval)."'");
			      $chkrow = mysql_num_rows($ga_sql);
			      if(!($chkrow)) {
			        $ga_row = fetch_array($ga_sql);
		            $gadate = date("Y-m-d H:i:s");
		            $ga_query = query("INSERT INTO $playerawards_table VALUES (NULL, '$id', '".addslashes($wpnaward_row["name"])."', '".$game["date"]."');");
			        $gt_sql = query("SELECT COUNT(*) AS num FROM $playerawards_table WHERE player = '$id' GROUP BY player");
				    $gt_row = fetch_array($gt_sql);
				    $up_query = query("UPDATE $players_table SET wpn_awards = wpn_awards + 1 WHERE id = '$id'");
		          } else {
				  }
				}
		      }
			}
	      }
        }
	  }
    }
    
    if($status == "player_stats") {
      $tmp = array();
      $tmp = explode(" ", $line);
      foreach($tmp as $field => $value) {
        $player[$player_data[$field]] = $tmp[$field];
      }
	  
      $player["name"]   = base64_encode($player["name"]);      
      $player["name"]   = GetPlayerAlias($player["name"]);
      $player_id        = GetPlayer($player["name"]);
	  $player_rec       = GetNcVal($bmt);
      $player_record_id = GetPlayerRecord($player_id, $game["gametype"], $server_id);
	  $player_game_id   = GetPlayerGame($player_id, $game_id, $player["team"]);
      $map_record_id    = GetMapRecord($map_id, $player_record_id);
	  $player_mrecord_id = GetMonthPlayerRecord($player_id, $game["gametype"], $server_id, $game["date"]);
      $map_mrecord_id    = GetMonthMapRecord($map_id, $player_record_id);
	  $history_name     = GetServerHistory($server_id, $player["name"], $game["date"]);
	  storePlayerIp($player_id, $player["ip"], $game["date"]);
	  
      $player["score1"]    = 0;
      $player["score2"]    = 0;
      $player["score3"]    = 0;
      $player["num_games"] = 0;
      $player["num_wins"]  = 0;
	  $newPlayerID         = 0;
	
      switch($game["gametype"]) {
        case "Deathmatch":            {$player["score1"] = $player["experience"];                                                                                                   } break;
        case "Team Deathmatch":       {$player["score1"] = $player["experience"];                                                                                                   } break;
        case "Team King of the Hill": {$player["score1"] = $player["zonetime"]; $player["score2"] = $player["tkothattackkills"]; $player["score3"] = $player["tkothdefendkills"]; } break;
        case "Capture the Flag":      {$player["score1"] = $player["flags"];      $player["score2"] = $player["flagsaves"];        $player["score3"] = $player["flagcarrierkills"]; } break; 
        case "Flagball":              {$player["score1"] = $player["flags"];                                                       $player["score3"] = $player["flagcarrierkills"]; } break; 
        case "Search and Destroy":    {$player["score1"] = $player["targets"];    $player["score2"] = $player["sdadattackkills"];  $player["score3"] = $player["sdaddefendkills"];  } break; 
        case "Attack and Defend":     {$player["score1"] = $player["targets"];    $player["score2"] = $player["sdadattackkills"];  $player["score3"] = $player["sdaddefendkills"];  } break; 
      }
       
	  $plyrRes = '0';
	          
      if($game["gametype"] != "Deathmatch") {
          $player["num_games"] = 1;
          if($game["winner"] == $player["team"] && $player["team"] != 0) {
            $player["num_wins"] = 1;
			$plyrRes = '1';
          } elseif($game["winner"] == 0) {
            $player["num_draws"] = 1;
			$plyrRes = '2';
		  }
      } else {
	        $player["num_games"] = 1;
		    query("UPDATE $players_table SET dm_value = dm_value + '".$player["experience"]."' WHERE id='".$player_id."'");
	  } 
	  
      query("UPDATE $stats_table SET
                 time         = time         + '".$player["timer"]."'       ,
                 kills        = kills        + '".$player["kills"]."'       ,
                 deaths       = deaths       + '".$player["deaths"]."'      ,
                 suicides     = suicides     + '".$player["suicides"]."'    ,
                 murders      = murders      + '".$player["murders"]."'     ,
                 headshots    = headshots    + '".$player["headshots"]."'   ,
                 knifings     = knifings     + '".$player["knifings"]."'    ,
                 medic_saves  = medic_saves  + '".$player["medsaves"]."'    ,
                 revives      = revives      + '".$player["revives"]."'     ,
                 pspattempts  = pspattempts  + '".$player["pspattempts"]."' ,
                 psptakeovers = psptakeovers + '".$player["psptakeovers"]."',
                 doublekills  = doublekills  + '".$player["doublekills"]."' ,
		             score_1      = score_1      + '".$player["score1"]."'      ,
		             score_2      = score_2      + '".$player["score2"]."'      ,
		             score_3      = score_3      + '".$player["score3"]."'      ,
		             games        = games        + '".$player["num_games"]."'   ,
		             wins         = wins         + '".$player["num_wins"]."'    , 
					 draws        = draws        + '".$player["num_draws"]."'   ,
		             last_played  = '".$game["date"]."'
               WHERE id='".$player_record_id."'");
	  
	  	query("UPDATE $stats_m_table SET
                   time         = time         + '".$player["timer"]."'       ,
                   kills        = kills        + '".$player["kills"]."'       ,
                   deaths       = deaths       + '".$player["deaths"]."'      ,
                   suicides     = suicides     + '".$player["suicides"]."'    ,
                   murders      = murders      + '".$player["murders"]."'     ,
                   headshots    = headshots    + '".$player["headshots"]."'   ,
                   knifings     = knifings     + '".$player["knifings"]."'    ,
                   medic_saves  = medic_saves  + '".$player["medsaves"]."'    ,
                   revives      = revives      + '".$player["revives"]."'     ,
                   pspattempts  = pspattempts  + '".$player["pspattempts"]."' ,
                   psptakeovers = psptakeovers + '".$player["psptakeovers"]."',
                   doublekills  = doublekills  + '".$player["doublekills"]."' ,
		           score_1      = score_1      + '".$player["score1"]."'      ,
		           score_2      = score_2      + '".$player["score2"]."'      ,
	               score_3      = score_3      + '".$player["score3"]."'      ,
                   games        = games        + '".$player["num_games"]."'   ,
		           wins         = wins         + '".$player["num_wins"]."'    ,
				   draws        = draws        + '".$player["num_draws"]."'   ,
		           last_played  = '".$game["date"]."'
                 WHERE id='".$player_mrecord_id."'");

	  if($player_game_id == '-1') {
	  	$player["statString"] = $player["kills"]."_".$player["deaths"]."_".$player["murders"]."_".
				                $player["suicides"]."_".$player["knifings"]."_".$player["headshots"]."_".
                                $player["medsaves"]."_".$player["revives"]."_".$player["pspattempts"]."_".
                                $player["psptakeovers"]."_".$player["doublekills"]."_".$player["score1"]."_".
		                        $player["score2"]."_".$player["score3"]."_".$player["timer"];
		query("INSERT INTO $playergames_table VALUES(
		             NULL, '".$game_id."', '".$player_id."', '".$player["ip"]."', '".$player["experience"]."', 
					 '".$player["statString"]."', '".$player["team"]."', '')");
		$npSql = query("SELECT id FROM $playergames_table WHERE game_id = '".$game_id."' 
		                                                 AND player = '".$player_id."' AND team = '".$player["team"]."'");
		$npRow = fetch_array($npSql);
		$player_game_id = $npRow["id"];
	  } else {
	  	$pSql = query("SELECT * FROM $playergames_table WHERE id = '".$player_game_id."'");
		$pRow = fetch_array($pSql);
		$sSpl = explode("_", $pRow["stats"]);
		$player["statString"] = $player["kills"] + $sSpl[0];
		$player["statString"] .= "_".($player["deaths"] + $sSpl[1]);
		$player["statString"] .= "_".($player["murders"] + $sSpl[2]);
		$player["statString"] .= "_".($player["suicides"] + $sSpl[3]);
		$player["statString"] .= "_".($player["knifings"] + $sSpl[4]);
		$player["statString"] .= "_".($player["headshots"] + $sSpl[5]);
		$player["statString"] .= "_".($player["medsaves"] + $sSpl[6]);
		$player["statString"] .= "_".($player["revives"] + $sSpl[7]);
		$player["statString"] .= "_".($player["pspattempts"] + $sSpl[8]);
		$player["statString"] .= "_".($player["psptakeovers"] + $sSpl[9]);
		$player["statString"] .= "_".($player["doublekills"] + $sSpl[10]);
		$player["statString"] .= "_".($player["score1"] + $sSpl[11]);
		$player["statString"] .= "_".($player["score2"] + $sSpl[12]);
		$player["statString"] .= "_".($player["score3"] + $sSpl[13]);
		$player["statString"] .= "_".($player["timer"] + $sSpl[14]);
		query("UPDATE $playergames_table SET 
		             experience = experience + '".$player["experience"]."' , 
					 stats      = '".$player["statString"]."'
				  WHERE id='".$player_game_id."'");
	  }
               
      $rating  = 0;
      $rating += $points["kills"]       * $player["kills"];
      $rating += $points["deaths"]      * $player["deaths"];
      $rating += $points["suicides"]    * $player["suicides"];
      $rating += $points["murders"]     * $player["murders"];
      $rating += $points["headshots"]   * $player["headshots"];
      $rating += $points["knifings"]    * $player["knifings"];
      $rating += $points["medic_saves"] * $player["medsaves"];
      $rating += $points["revives"]     * $player["revives"];
      $rating += $points["pspattempts"] * $player["pspattempts"];
      $rating += $points["psptakeovers"]* $player["psptakeovers"];
      $rating += $points["doublekills"] * $player["doublekills"];
      $rating += $points["wins"]        * $player["num_wins"];
      if($game["gametype"] == "Team King of the Hill") {
        $rating += $points["zone"]       * $player["score1"] / 60;
        $rating += $points["zoneattack"] * $player["score2"];
        $rating += $points["zonedefend"] * $player["score3"];
      }
      if($game["gametype"] == "Capture the Flag" || $game["gametype"] == "Flagball") {
        $rating += $points["flags"]       * $player["score1"];
        $rating += $points["saves"]       * $player["score2"];
        $rating += $points["runnerkills"] * $player["score3"];
      }
      if($game["gametype"] == "Attack and Defend" || $game["gametype"] == "Search and Destroy") {
        $rating += $points["targets"]    * $player["score1"];
        $rating += $points["sdadattack"] * $player["score2"];
        $rating += $points["sdaddefend"] * $player["score3"];
      }
              
      query("UPDATE $players_table SET
                 rating   = rating   + '".$rating."', 
				 m_rating = m_rating + '".$rating."'
               WHERE id='".$player_id."'");
	                   
      query("UPDATE $mapstats_table SET
                 time        = time     + '".$player["timer"]."'    ,
                 kills       = kills    + '".$player["kills"]."'    ,
                 deaths      = deaths   + '".$player["deaths"]."'   ,
                 score       = score    + '".$player["score1"]."'    
               WHERE id='".$map_record_id."'");
	   
	   query("UPDATE $mapstats_m_table SET
                   time        = time     + '".$player["timer"]."'    ,
                   kills       = kills    + '".$player["kills"]."'    ,
                   deaths      = deaths   + '".$player["deaths"]."'   ,
                   score       = score    + '".$player["score1"]."'    
                 WHERE id='".$map_mrecord_id."'");

    }
    
    if($status == "weapon") {
      $tmp = array();
      $tmp = explode(" ", $line);
      foreach($tmp as $field => $value) {
        $weapon[$weapon_data[$field]] = $tmp[$field];
      }
      
	  $wpnStr = "";
	  
      if(isset($wpn_name[$game["mod"]][$weapon["code"]])) {
        $weapon["name"]   = $wpn_name[$game["mod"]][$weapon["code"]];
        $weapon_id        = GetWeapon($weapon["name"]);
        $weapon_record_id = GetWeaponRecord($weapon_id, $player_record_id);
		$weapon_mrecord_id = GetMonthWeaponRecord($weapon_id, $player_record_id);
        $wpn_rating       = $wpn_points[$weapon["name"]] * $weapon["kills"];
        query("UPDATE $weaponstats_table SET 
                   time     = time    + '".$weapon["timer"]."' ,
                   kills    = kills   + '".$weapon["kills"]."' ,
                   shots    = shots   + '".$weapon["shots"]."' 
                 WHERE id='".$weapon_record_id."'");
		
		query("UPDATE $weaponstats_m_table SET 
                   time     = time    + '".$weapon["timer"]."' ,
                   kills    = kills   + '".$weapon["kills"]."' ,
                   shots    = shots   + '".$weapon["shots"]."' 
                 WHERE id='".$weapon_mrecord_id."'");
		
		$wSql = query("SELECT wpns FROM $playergames_table WHERE id = '".$player_game_id."'");
		$wRow = fetch_array($wSql);		
		$wpSpl = explode(chr(10), $wRow["wpns"]);
		$fndWpn = false;
		foreach($wpSpl as $dbStr) {
			$strSpl = explode("_", $dbStr);
			if($strSpl[0] == $weapon["name"]) {
				if(strlen($dbStr) > 0) {
					$wpnStr .= $weapon["name"]."_".($weapon["timer"]+$strSpl[1])."_".($weapon["kills"]+$strSpl[2]).
							 "_".($weapon["shots"]+$strSpl[3]).chr(10);
					$fndWpn = true;
				}
			} else {
				if(strlen($dbStr) > 0) {
					$wpnStr .= $dbStr.chr(10);
				}
			}
		}
		
		if(!$fndWpn) $wpnStr .= $weapon["name"]."_".$weapon["timer"]."_".$weapon["kills"]."_".$weapon["shots"].chr(10);
		
		query("UPDATE $playergames_table SET 
					 wpns         = '".$wpnStr."' 
				 WHERE id='".$player_game_id."'");
		
		if($weapon["name"] == "M21" || $weapon["name"] == "M24" || $weapon["name"] == "MCRT .300 Tactical" 
		   || $weapon["name"] == "Barrett .50 Cal" || $weapon["name"] == "PSG1" || $weapon["name"] == "SIG 550 Sniper" 
		   || $weapon["name"] == "M-20" || $weapon["name"] == "Walther 2000") {
		  query("UPDATE $stats_table SET 
		             sniper_kills = sniper_kills + '".$weapon["kills"]."' 
				   WHERE id='".$player_record_id."'");
		  query("UPDATE $stats_m_table SET 
		             sniper_kills = sniper_kills + '".$weapon["kills"]."' 
				   WHERE id='".$player_mrecord_id."'");
		}
      
        query("UPDATE $players_table SET
                   rating = rating   + '".$wpn_rating."', 
				 m_rating = m_rating + '".$wpn_rating."' 
                 WHERE id='".$player_id."'");
	  } 
    }
  } 
  //$fdate = substr($game["date"], 0, 10);
    $de1 = explode(" ", $game["date"]); $de2 = explode("-", $de1[0]); //date fix
  if(strlen($de2[1]) == 1) $de2[1] = "0".$de2[1]; //date fix
  if(strlen($de2[2]) == 1) $de2[2] = "0".$de2[2]; //date fix
  
  $fdate = $de2[0]."-".$de2[1]."-".$de2[2]; //date fix
  $sql = query("SELECT COUNT(name) as num, date FROM $serverhistory_table WHERE serverid = '$server_id' AND players = '' AND date < '$fdate' GROUP BY date");
  while($row = fetch_array($sql)) {
    if($row["num"] > 0) { 
	  query("INSERT INTO $serverhistory_table VALUES (NULL, '$server_id', '".$row["num"]."', '', '".$row["date"]."')");
      query("DELETE FROM $serverhistory_table WHERE serverid = '$server_id' AND players = '' AND date < '$fdate'");
    }
  }
  
  query("UPDATE $servers_table SET age = '0' WHERE id = '$server_id'");
  if($game["gametype"] == "Deathmatch") {
    if($game["numplayers"] > 0) {
      $sql = query("SELECT * FROM $players_table ORDER BY dm_value DESC LIMIT 1");
      $row = fetch_array($sql);
      query("UPDATE $stats_table SET wins = wins + 1 WHERE player='".$row["id"]."' 
	           AND game_type LIKE 'Deathmatch' AND server = '".$server_id."'");
      query("UPDATE $stats_m_table SET wins = wins + 1 WHERE player='".$row["id"]."' 
	           AND game_type LIKE 'Deathmatch' AND server = '".$server_id."'");
	  query("UPDATE $playergames_table SET result = 1 WHERE player='".$row["id"]."' 
	           AND date_played = '".$game["date"]."'");
	  query("UPDATE $players_table SET dm_value = 0");
	}
  }
	
  $log = "Stats imported ".$game["date"];
  return $log;
}

function StatusUpdate($data, $serverid) {

  global $bmt_pre, $wpn_points, $points, $percenttowin, $gametypes, $wpn_name, $bmt_pre;
  
  $log = "";
  
  $ip       = "0.0.0.0";
  $thetime  = time();
  
  // ECHO "SELECT * FROM ".$bmt_pre."servers WHERE serverid='".$serverid."';";
  // exit;
  $query = query("SELECT * FROM ".$bmt_pre."servers WHERE serverid='".$serverid."';");

  if(num_rows($query) == 0) {
    $log .= "Invalid server id code (".$serverid.")";
    return $log;
  }
  
  $data_lines = explode("\n", $data);
  $line_count = count($data_lines);
  
  $status = "blank";
  
  $game        = array();
  $player      = array();
  
  $game_data = array(
    0 => "timer",
    1 => "date",
    2 => "gametype",
    3 => "dedicated",
    4 => "servername",
    5 => "mapname",
    6 => "maxplayers",
    7 => "numplayers",
    8 => "mod"
  );                             

  $player_data = array(
    0  => "timer",
    1  => "team",
    2  => "weapon"
  );
                              
  $num_players    = 0;
  $player_names   = "";
  $player_weapons = "";
  $player_teams   = "";

  $skip        = false;
  $skip_to     = "";
    
  for($i = 0; $i < $line_count; $i++) {

    $line = $data_lines[$i];
    $tag  = explode(" ", trim($line));
    $tag  = $tag[0];
    
    $offset = 0;
    
    switch($tag) {
      case "ServerID":    {$status = "server_id";    $offset = 8; } break;
      case "Game":        {$status = "game";         $offset = 5; } break;
      case "Player":      {$status = "player";       $offset = 8; } break;
      case "PlayerStats": {$status = "player_stats"; $offset = 14;} break;
      case "End":         {$status = "end";          $offset = 0; } break;
      default:            {$status = "blank";        $offset = 0; }
    }
    
    if($skip && $status != $skip_to) continue;
    
    $skip    = false;
    $skip_to = "";
    
    $line = trim(substr($line, $offset));

    if($status == "blank") {}
    
    if($status == "server_id") {}

    if($status == "game") {
      $tmp = array();
      $tmp = explode("__&__", $line);
      foreach($tmp as $field => $value) {
        $game[$game_data[$field]] = $tmp[$field];
      }
	  
	  $game["timer"] = str_replace("Game ", "", $game["timer"]);
      if($game["dedicated"] == "1") $game["dedicated"] = "Yes"; else $game["dedicated"] = "No";
      $game["gametype"] = $gametypes[$game["gametype"]];
      $game["mapname"] =str_replace("??", "", $game["mapname"]);
    }
    
    if($status == "player") {
      $num_players++;
      $player["name"] = $line;
    }
    
    if($status == "player_stats") {
      $tmp = array();
      $tmp = explode(" ", $line);
      foreach($tmp as $field => $value) {
        $player[$player_data[$field]] = $tmp[$field];
      }
      if($game["gametype"] == "Deathmatch") $player["team"] = "None";
      if($player["team"] == "1") $player["team"] = "Blue";
      if($player["team"] == "2") $player["team"] = "Red";
      if($num_players == 1) $separator = ""; else $separator = "\n";
      $player_names   .= $separator.base64_encode($player["name"]);
      $player_teams   .= $separator.$player["team"];
      if(isset($wpn_name[$game["mod"]][$player["weapon"]])) {
        $player_weapons .= $separator.$wpn_name[$game["mod"]][$player["weapon"]];
      } else {
        $player_weapons .= $separator;
      }
    }
    
	switch($game["mod"]) {
		case 7: $gameName = "Black Hawk Down"; break;
		case 8: $gameName = "BHD: Team Sabre"; break;
		case 9: $gameName = "BHD: Black Operations"; break;
		case 10: $gameName = "BHD: War on Terror"; break;
		case 16: $gameName = "BHD: Shock \'n Awe"; break;
	}
	
    if($status == "end") {
      query("UPDATE ".$bmt_pre."servers SET 
                 ip             = '".$ip."'                                    ,
                 map_name       = '".base64_encode($game["mapname"])."'    ,
                 server_name    = '".base64_encode($game["servername"])."' ,
                 dedicated      = '".$game["dedicated"]."'                 ,
                 game_type      = '".$game["gametype"]."'                  ,
                 max_players    = '".$game["maxplayers"]."'                ,
                 time           = '".$thetime."'                               ,
                 game           = '".$gameName."'                              ,
                 num_players    = '".$num_players."'                           ,
                 player_names   = '".$player_names."'                          ,
                 player_teams   = '".$player_teams."'                          ,
                 player_weapons = '".$player_weapons."'                        ,
				 age            = '".$game["timer"]."' 
               WHERE serverid='".$serverid."'");
    }
  }

  $log .= "Status updated successfully!";
  return $log;
 
}

function StatusReport($data, $serverid) {

    global $bmt_pre, $weapons_table, $weaponstats_table, $wpn_points, $points, $percenttowin, $gametypes, $wpn_name;
	
	$player_data = array(
		0  => "suicides"        ,
		1  => "murders"         ,
		2  => "kills"           ,
		3  => "deaths"          ,
		4  => "zone_time"        ,
		5  => "flags_captured"           ,
		6  => "flags_saved"       ,
		7  => "targets_destroyed"         ,
		8  => "revives"         ,
		9  => "medic_saves"        ,
		10 => "psp_attempts"     ,
		11 => "psp_takeovers"    ,
		12 => "flag_runners_killed",
		13 => "double_kills"     ,
		14 => "headshots"       ,
		15 => "knifings"        ,
		16 => "sniperkills"     ,
		17 => "tkoth_zone_attack_kills",
		18 => "tkoth_zone_defend_kills",
		19 => "sd_ad_defend_kills" ,
		20 => "sdadpolicekills" ,
		21 => "sd_ad_attack_kills" ,
		22 => "sdadsecurekills" ,
		23 => "shotsperkill"    ,
		24 => "experience"      ,
		25 => "team"            ,
		26 => "playedtillend"   ,
		27 => "time_played"
	);
	
	$lines = array();
	
	$lines = explode("\n", $data); 
	for($i = 0; $i < sizeof($lines); $i++) {
		$lines[$i] = trim($lines[$i]);
	}
	
	$awardList = "";
	
	$gametype = $gametypes[$lines[0]];
	
	for($i = 1; $i <  sizeof($lines); $i++) {
		if(!empty($lines[$i])) {
			if(substr($lines[$i], 0, 7) == "Player ") {
				$plyrName = "";
				$plyrID   = '0';
				
				$lines[$i]   = substr($lines[$i], 7);
				$plyrDetails = explode("__&__", $lines[$i]);
				$plyrName    = base64_encode($plyrDetails[0]);
				$sql         = query("SELECT * FROM ".$bmt_pre."players WHERE name = '".$plyrName."'");
				$row         = @fetch_assoc($sql);
				$plyrID      = $row["id"];
			}
			
			if(substr($lines[$i], 0, 7) == "PlayerS" && $plyrID > '0') {
				$lines[$i] = substr($lines[$i], 12);
				$tmp = array();
			    $tmp = explode(" ", $lines[$i]);
			    foreach($tmp as $field => $value) {
				     $player[$player_data[$field]] = $tmp[$field];
			    }

				switch($gametype) {
					case "Deathmatch":
					    $player["score1"] = $player["experience"];
					break;
					case "Team Deathmatch":
					    $player["score1"] = $player["experience"];
					break;
					case "Team King of the Hill":
					    $player["score1"] = $player["zone_time"]; 
						$player["score2"] = $player["tkothattackkills"]; 
						$player["score3"] = $player["tkothdefendkills"];
					break;
					case "Capture the Flag":
					    $player["score1"] = $player["flags"];
						$player["score2"] = $player["flagsaves"]; 
						$player["score3"] = $player["flagcarrierkills"];
					break; 
					case "Flagball":
					    $player["score1"] = $player["flags"];
						$player["score3"] = $player["flagcarrierkills"];
					break; 
					case "Search and Destroy":
					    $player["score1"] = $player["targets"];
						$player["score2"] = $player["sdadattackkills"];
						$player["score3"] = $player["sdaddefendkills"];
					break; 
					case "Attack and Defend":
					    $player["score1"] = $player["targets"];
						$player["score2"] = $player["sdadattackkills"];
						$player["score3"] = $player["sdaddefendkills"]; 
					break; 
				  }
				
				$rating  = 0;
			    $rating += $points["kills"]       * $player["kills"];
			    $rating += $points["deaths"]      * $player["deaths"];
			    $rating += $points["suicides"]    * $player["suicides"];
			    $rating += $points["murders"]     * $player["murders"];
			    $rating += $points["headshots"]   * $player["headshots"];
			    $rating += $points["knifings"]    * $player["knifings"];
			    $rating += $points["medic_saves"] * $player["medsaves"];
			    $rating += $points["revives"]     * $player["revives"];
			    $rating += $points["pspattempts"] * $player["pspattempts"];
			    $rating += $points["psptakeovers"]* $player["psptakeovers"];
			    $rating += $points["doublekills"] * $player["doublekills"];
			    $rating += $points["wins"]        * $player["num_wins"];
			    if($gametype == "Team King of the Hill") {
					$rating += $points["zone"]       * $player["score1"] / 60;
					$rating += $points["zoneattack"] * $player["score2"];
					$rating += $points["zonedefend"] * $player["score3"];
			    }
			    if($gametype == "Capture the Flag" || $gametype == "Flagball") {
					$rating += $points["flags"]       * $player["score1"];
					$rating += $points["saves"]       * $player["score2"];
					$rating += $points["runnerkills"] * $player["score3"];
			    }
			    if($gametype == "Attack and Defend" || $gametype == "Search and Destroy") {
					$rating += $points["targets"]    * $player["score1"];
					$rating += $points["sdadattack"] * $player["score2"];
					$rating += $points["sdaddefend"] * $player["score3"];
			    }
				
				$pnm = base64_encode($value);
		  		$name = query("SELECT * FROM ".$bmt_pre."players WHERE name = '".$pnm."'");
				  
				   $n_row = fetch_array($name);
				   
				    $rating + $row["rating"];
				    
					$rSql = query("SELECT * FROM ".$bmt_pre."ranks WHERE rating > '".$row["rating"]."' LIMIT 0, 1");
					$rNum = @num_rows($rSql);
					if($rNum > '0') {
						$rRow = fetch_assoc($rSql);
						$ttlRating = $rating + $row["rating"];
						
						if($ttlRating >= $rRow["rating"]) {
							$gr_sql = query("SELECT * FROM ".$bmt_pre."playerawards WHERE player = '".$plyrID."' 
									                       AND award = '".addslashes($rRow["name"])."'");
							$gr_chkrow = @num_rows($gr_sql);
							if(!($gr_chkrow)) {
								$gr_row = fetch_array($gr_sql);
								$gr_query = query("INSERT INTO ".$bmt_pre."playerawards VALUES (NULL, '".$plyrID."', '".
								addslashes($rRow["name"])."', '".date("Y-m-d H:i:s")."');");
								if($awardList == "") {
									$awardList = $plyrDetails[0]." has been Promoted to ".$rRow["name"];
								} else {
									$awardList .= "__&__".$plyrDetails[0]." has been Promoted to ".$rRow["name"];
								}
							}
						}
					}
					
            		$id = $n_row["id"];
					$award_query = query("SELECT * FROM ".$bmt_pre."awards WHERE type = 'A'");
					$stats_query = query("SELECT * FROM ".$bmt_pre."stats WHERE ".$bmt_pre."stats.player='".$plyrID."'");
					$award_stats = array();
					$stats_row   = array();
					while($stats_row = fetch_assoc($stats_query)) {
					    foreach($stats_row as $field => $value) {
							if($field * 1 == 0 && $field != "0") {
							    $stats_field = "";
							    switch($field) {
									case "kills":        $stats_field = "kills";           break;
									case "deaths":       $stats_field = "deaths";          break;
									case "murders":      $stats_field = "murders";         break;
									case "suicides":     $stats_field = "suicides";        break;
									case "knifings":     $stats_field = "knifings";        break;
									case "headshots":    $stats_field = "headshots";       break;
									case "medic_saves":  $stats_field = "medic_saves";     break;
									case "revives":      $stats_field = "revives";         break;
									case "pspattempts":  $stats_field = "psp_attempts";    break;
									case "psptakeovers": $stats_field = "psp_takeovers";   break;
									case "doublekills":  $stats_field = "double_kills";    break;
									case "time":         $stats_field = "time_played";     break;
									case "games":        $stats_field = "games_completed"; break;
									case "wins":         $stats_field = "team_games_won";  break;
								}
							}
						    if($stats_row["game_type"] == "Team King of the Hill") {
								switch($field) {
								    case "score_1": $stats_field = "zone_time";               break;
								    case "score_2": $stats_field = "tkoth_zone_attack_kills"; break;
								    case "score_3": $stats_field = "tkoth_zone_defend_kills"; break;
								}
						    }
							if($stats_row["game_type"] == "Capture the Flag" || $stats_row["game_type"] == "Flagball") {
								switch($field) {
								    case "score_1": $stats_field = "flags_captured";      break;
								    case "score_2": $stats_field = "flags_saved";         break;
								    case "score_3": $stats_field = "flag_runners_killed"; break;
								}
							}
						    if($stats_row["game_type"] == "Search and Destroy" || 
							   $stats_row["game_type"] == "Attack and Defend") {
								switch($field) {
								    case "score_1": $stats_field = "targets_destroyed";  break;
								    case "score_2": $stats_field = "sd_ad_attack_kills"; break;
								    case "score_3": $stats_field = "sd_ad_defend_kills"; break;
								}
						    }
						    if($stats_field != "") {
								if(isset($award_stats[$stats_field])) {
								    $award_stats[$stats_field] += $value;
									$award_stats[$stats_field] += $player[$stats_field];
								} else {
								    $award_stats[$stats_field] = $value;
									$award_stats[$stats_field] += $player[$stats_field];
								}
						    }
						}
					}
					
					$awards = array();
					$award  = array();
					$count  = 0;
					while($award_row = fetch_array($award_query)) {
						if(isset($award_stats[$award_row["field"]])) {
							if($award_stats[$award_row["field"]] >= $award_row["value"]) {
								$awrn = array($award_row["name"]);
								foreach($awrn as $awval) {
									$ga_sql = query("SELECT * FROM ".$bmt_pre."playerawards WHERE player = '".$plyrID."' 
									                       AND award = '".addslashes($awval)."'");
									$chkrow = num_rows($ga_sql);
									if(!($chkrow)) {
										$ga_row = fetch_array($ga_sql);
										$ga_query = query("INSERT INTO ".$bmt_pre."playerawards VALUES (NULL, '".$plyrID."', '".
										addslashes($award_row["name"])."', '".date("Y-m-d H:i:s")."');");
										if($awardList == "") {
											$awardList = $plyrDetails[0]." has obtained ".$award_row["name"];
										} else {
											$awardList .= "__&__".$plyrDetails[0]." has obtained ".$award_row["name"];
										}
										$gt_sql = query("SELECT COUNT(*) AS num FROM ".$bmt_pre."playerawards WHERE player = 
														  '".$plyrID."' GROUP BY player");
										$gt_row = fetch_array($gt_sql);
										$up_query = query("UPDATE ".$bmt_pre."players SET awards = awards + 1 WHERE id = '".$plyrID."'");
									}
							    }
							}
						}
					}
				
		    }
	    }
	}
	
	echo $awardList;
}

function FormatTime($seconds) {
  $hours    = floor($seconds/3600);
  $seconds -= $hours * 3600; 
  $minutes  = floor($seconds/60);
  $seconds -= $minutes * 60; 
  return sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
}
function GetRanking($id) {
  global $bmt_pre, $players_table;
  $list = query("SELECT ".$bmt_pre."players.id FROM ".$bmt_pre."players WHERE ".$bmt_pre."players.rating > 0 GROUP BY ".$bmt_pre."players.id ORDER BY rating DESC, name ASC");
  while ($line = mysql_fetch_array($list, MYSQL_ASSOC)) {
	$i++;
    foreach ($line as $col_value) {
      if($col_value != $id){
	    $ni = $i+1;
	  } else {
	    $st = array('','2','3','4','5','6','7','8','9','10');
	    $nd = array('','2','3','4','5','6','7','8','9','10');
	    $rd = array('','2','3','4','5','6','7','8','9','10');
		$th = array('','1','2','3','4','5','6','7','8','9','10');
		foreach($st as $value){
	      switch ($ni) {
            case $value.'1': $nni = $ni."st"; break;
		  }
	    }
	    foreach($nd as $value){
	      switch ($ni) {
            case $value.'2': $nni = $ni."nd"; break;
		  }
	    }
		foreach($rd as $value){
	      switch ($ni) {
            case $value.'3': $nni = $ni."rd"; break;
		  }
	    }
		foreach($th as $value){
	      switch ($ni) {
            case $value.'0': $nni = $ni."th"; break;
			case $value.'4': $nni = $ni."th"; break;
			case $value.'5': $nni = $ni."th"; break;
			case $value.'6': $nni = $ni."th"; break;
			case $value.'7': $nni = $ni."th"; break;
			case $value.'8': $nni = $ni."th"; break;
			case $value.'9': $nni = $ni."th"; break;
			case '1'.$value: $nni = $ni."th"; break;
		  }
	    }
		switch ($ni) {
           case 0 : $nni = "1st"; break;
		  }
	    return $nni;
	  }
    }
  }
}

function GetMonthlyRanking($id) {
  global $bmt_pre, $players_table;
  $list = mysql_query("SELECT ".$bmt_pre."players.id FROM ".$bmt_pre."players WHERE ".$bmt_pre."players.m_rating > 0 GROUP BY ".$bmt_pre."players.id ORDER BY m_rating DESC, name ASC");
  while ($line = mysql_fetch_array($list, MYSQL_ASSOC)) {
	$i++;
    foreach ($line as $col_value) {
      if($col_value != $id){
	    $ni = $i+1;
	  } else {
	    $st = array('','2','3','4','5','6','7','8','9','10');
	    $nd = array('','2','3','4','5','6','7','8','9','10');
	    $rd = array('','2','3','4','5','6','7','8','9','10');
		$th = array('','1','2','3','4','5','6','7','8','9','10');
		foreach($st as $value){
	      switch ($ni) {
            case $value.'1': $nni = $ni."st"; break;
		  }
	    }
	    foreach($nd as $value){
	      switch ($ni) {
            case $value.'2': $nni = $ni."nd"; break;
		  }
	    }
		foreach($rd as $value){
	      switch ($ni) {
            case $value.'3': $nni = $ni."rd"; break;
		  }
	    }
		foreach($th as $value){
	      switch ($ni) {
            case $value.'0': $nni = $ni."th"; break;
			case $value.'4': $nni = $ni."th"; break;
			case $value.'5': $nni = $ni."th"; break;
			case $value.'6': $nni = $ni."th"; break;
			case $value.'7': $nni = $ni."th"; break;
			case $value.'8': $nni = $ni."th"; break;
			case $value.'9': $nni = $ni."th"; break;
			case '1'.$value: $nni = $ni."th"; break;
		  }
	    }
		switch ($ni) {
           case 0 : $nni = "1st"; break;
		  }
	    return $nni;
	  }
    }
  }
}

function GetLogins($value, $serverid) {
  global $bmt_pre, $serverhistory_table, $dow, $today, $yesterday;
  
  switch($dow) {
    case "Monday"    : $tws = date("d")     ; $twe = date("d") + 6 ; $lws = date("d") - 7 ; $lwe = date("d") - 1 ;break;
	case "Tuesday"   : $tws = date("d") - 1 ; $twe = date("d") + 5 ; $lws = date("d") - 8 ; $lwe = date("d") - 2 ;break;
	case "Wednesday" : $tws = date("d") - 2 ; $twe = date("d") + 4 ; $lws = date("d") - 9 ; $lwe = date("d") - 3 ;break;
    case "Thursday"  : $tws = date("d") - 3 ; $twe = date("d") + 3 ; $lws = date("d") -10 ; $lwe = date("d") - 4 ;break;
	case "Friday"    : $tws = date("d") - 4 ; $twe = date("d") + 2 ; $lws = date("d") -11 ; $lwe = date("d") - 5 ;break;
	case "Saturday"  : $tws = date("d") - 5 ; $twe = date("d") + 1 ; $lws = date("d") -12 ; $lwe = date("d") - 6 ;break;
	case "Sunday"    : $tws = date("d") - 6 ; $twe = date("d")     ; $lws = date("d") -13 ; $lwe = date("d") - 7 ;break;
  }
  
  $start_date = mktime(0, 0, 0, date("m")  , $tws, date("Y"));
  $this_start = date("Y-m-d", $start_date) ;
  $end_date = mktime(0, 0, 0, date("m")  , $twe, date("Y"));
  $this_end = date("Y-m-d", $end_date) ;
  $last_start_date =  mktime(0, 0, 0, date("m")  , $lws, date("Y"));
  $last_start = date("Y-m-d", $last_start_date) ;
  $last_end_date = mktime(0, 0, 0, date("m")  , $lwe, date("Y"));
  $last_end = date("Y-m-d", $last_end_date) ;
  $month_s_date = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
  $month_start = date("Y-m-01", $month_s_date) ;
  $month_e_date = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
  $month_end = date("Y-m-t", $month_e_date) ;
  $lmonth_s_date = mktime(0, 0, 0, date("m") - 1  , date("d") , date("Y"));
  $lmonth_start = date("Y-m-01", $lmonth_s_date) ;
  $lmonth_e_date = mktime(0, 0, 0, date("m") - 1  , date("d") , date("Y"));
  $lmonth_end = date("Y-m-t", $lmonth_e_date) ;
  $year_s_date = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
  $year_start = date("Y-01-01", $year_s_date) ;
  $year_e_date = mktime(0, 0, 0, date("m")  , date("d") , date("Y"));
  $year_end = date("Y-12-t", $year_e_date) ;
  $lyear_s_date = mktime(0, 0, 0, date("m") , date("d") , date("Y") - 1);
  $lyear_start = date("Y-01-01", $lyear_s_date) ;
  $lyear_e_date = mktime(0, 0, 0, date("m")  , date("d") , date("Y") - 1);
  $lyear_end = date("Y-12-t", $lyear_e_date) ;

  
  switch($value) {
    case "todays"      : $criteria = 'COUNT(name) as num' ; $conditions = "date = '".$today."'"; break;
    case "yesterdays"  : $criteria = 'players as num'     ; $conditions = "date = '".$yesterday."'"; break;
    case "this_week"   : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$this_start."' AND date <= '".$this_end."'"; break;
    case "last_week"   : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$last_start."' AND date <= '".$last_end."'"; break;
    case "this_month"  : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$month_start."' AND date <= '".$month_end."'"; break;
	case "last_month"  : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$lmonth_start."' AND date <= '".$lmonth_end."'"; break;
    case "this_year"   : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$year_start."' AND date <= '".$year_end."'"; break;
    case "last_year"   : $criteria = 'SUM(players) as num'; $conditions = "date >= '".$lyear_start."' AND date <= '".$lyear_end."'"; break;
  }
  
  $sql = query("SELECT ".$criteria." FROM ".$bmt_pre."serverhistory WHERE serverid = '".$serverid."' AND ".$conditions."");
  $row = fetch_array($sql);
  return $row["num"];
}


function GetWeaponLevels($plid, $wpnid) {
  global $bmt_pre, $stats_table, $weapons_table, $weaponstats_table;
  
  if($wpnid == "CAR15/M203 - Grenade" || $wpnid == "M16/M203 - Grenade") $type = "weapontype1";
	
  if($wpnid == "CAR15" || $wpnid == "CAR15/M203" || $wpnid == "M16" || $wpnid == "M249 SAW" || 
    $wpnid == "M16/M203" || $wpnid == "G3" || $wpnid == "G36" || $wpnid == "M240" || $wpnid == "M60") $type = "weapontype2";
  
  if($wpnid == "M24" || $wpnid == "Barrett .50 Cal" || $wpnid == "PSG1" || 
    $wpnid == "MCRT .300 Tactical" || $wpnid == "M21" || $wpnid == "MP5" || $wpnid == "Knife") $type = "weapontype3";
  
  if($wpnid == "Colt .45" || $wpnid == "M9 Beretta" || $wpnid == "AT4" || $wpnid == "Remington Shotgun") $type = "weapontype4";
  
  if($wpnid == "50 Cal Humvee" || $wpnid == "MiniGun" || $wpnid == "Grenade Launcher" || $wpnid == "50 Cal Emplacement" || 
    $wpnid == "E M Canon" || $wpnid == "50 Cal Truck" || $wpnid == "Frag Grenade" || $wpnid == "Claymore") $type = "weapontype5";


  switch($type) {
    case "weapontype1"   : $div = '50'; break;
    case "weapontype2"   : $div = '40'; break;
    case "weapontype3"   : $div = '30'; break;
    case "weapontype4"   : $div = '20'; break;
    case "weapontype5"   : $div = '10'; break;
  }

  $query = query("SELECT ".$bmt_pre."weapons.id, ".$bmt_pre."weapons.name, ".$bmt_pre."weapons.record, ".$bmt_pre."stats.id, 
                      sum(".$bmt_pre."weaponstats.kills) as kills, ".$bmt_pre."weaponstats.weapon, ".$bmt_pre."stats.player 
				    FROM ".$bmt_pre."weapons, ".$bmt_pre."weaponstats, ".$bmt_pre."stats 
				    WHERE ".$bmt_pre."weaponstats.weapon = ".$bmt_pre."weapons.id AND ".$bmt_pre."stats.id = ".$bmt_pre."weaponstats.record 
				    AND ".$bmt_pre."weapons.name LIKE '".$wpnid."' AND ".$bmt_pre."stats.player = '".$plid."' GROUP BY ".$bmt_pre."stats.player");
					
  $row = fetch_array($query);
  if($div < '1') {
  	$row["exp"] = '0';
  } else {
    $kills = $row["kills"]/$div;
    if(floor($kills) > 49) {
      $row["exp"] = 50;
    } else {
      $row["exp"] = floor($kills);
    }
  }
  return $row["exp"];
}

function GetGameLevels($id, $type, $group, $section) {
  global $bmt_pre, $players_table, $stats_table, $stats_m_table, $bmt_pre;
    
	if($section == "overall") {
	  $table = $stats_table;
	} elseif($section == "month") {
	  $table = $stats_m_table;
	}
	
    if($group == "player") {
	  $conditions = " AND  game_type LIKE '".$type."' AND ".$bmt_pre."players.id = '".$id."' GROUP BY ".$bmt_pre."players.id";
	} elseif($group == "squad") {
	  $conditions = " AND  game_type LIKE '".$type."' AND ".$bmt_pre."players.squad = '".$id."' GROUP BY ".$bmt_pre."players.squad";
	}
					 
	$data = query("SELECT ".$bmt_pre."players.id, ".$bmt_pre."players.squad, $table.player, SUM($table.kills) AS kills, 
	                   SUM($table.psptakeovers) AS psptakeovers, SUM($table.score_1) AS score_1, 
	                   SUM($table.score_2) AS score_2, SUM($table.score_3) AS score_3, SUM($table.games) AS games, SUM($table.time) AS time 
					 FROM $players_table, $table 
					 WHERE $table.player = $players_table.id 
					 $conditions");
    
	$row = fetch_array($data);
	
	if($row["kills"] === '0') $row["kills"] = 1;
	if($row["score_1"] === '0') $row["score_1"] = 1;
	if($row["score_2"] === '0') $row["score_2"] = 1;
	if($row["score_3"] === '0') $row["score_3"] = 1;
	if($row["games"] === '0') $row["games"] = 1;
	if($row["time"] === '0') $row["time"] = 1;
	if(!($row["games"])) $div = '1';
	
	switch($type) {
	  case "Deathmatch"           : $crit3 =  $row["kills"]/100       AND $div = '3'                                                                 ; break;
	  case "Team Deathmatch"      : $crit3 =  $row["kills"]/100       /*AND $crit4 = $row["psptakeovers"]-10*/ AND $div = '2'                            ; break;
	  case "Team King of the Hill": $crit3 =  ($row["score_1"]/60)/60 AND $crit4 = $row["score_2"]/15 AND $crit5 = $row["score_3"]/15 AND $div = '5' ; break;
	  case "Search and Destroy"   : $crit3 =  $row["score_1"]/25      AND $crit4 = $row["score_2"]/8  AND $crit5 = $row["score_3"]/8  AND $div = '5' ; break;
	  case "Attack and Defend"    : $crit3 =  $row["score_1"]/12      AND $crit4 = $row["score_2"]/12 AND $crit5 = $row["score_3"]/12 AND $div = '5' ; break;
	  case "Capture the Flag"     : $crit3 =  $row["score_1"]/10      AND $crit4 = $row["score_2"]/10 AND $crit5 = $row["score_3"]/10 AND $div = '5' ; break;
	  case "Flagball"             : $crit3 =  $row["score_1"]/6       AND $crit5 = $row["score_3"]/6  AND $div = '4'                                 ; break;
	}
	
	if($group == "squad") {
	  $squad = query("SELECT COUNT(id) AS num FROM $players_table WHERE squad = '$id'");
	  $srow = fetch_array($squad);
	  $div = $div*$srow["num"];
	}
	$crit1 = $row["games"]/10;
	$crit2 = ($row["time"]/60)/60;
	$crit = $crit1 + $crit2 + $crit3 + $crit4 + $crit5;
	if($crit < 1){
	  $row[$type] = "Level 0";
	} elseif(!($crit)) {
	  $row[$type] = "Level 0";
	} else {
	  $level = $crit/$div;
	  if(floor($level) > 49) {$row[$type] = "Level 50";} else {$row[$type] = "Level ".floor($level)."";}
    }
  return $row[$type];
}

function GetWeaponUsage($value, $id, $type, $server, $gametype) {
  global $bmt_pre, $players_table, $stats_table, $weaponstats_table, $weapons_table;
 
  if($server   != -1)    $conditions .= " AND $stats_table.server='$server' ";
  if($gametype != "All") $conditions .= " AND $stats_table.game_type='$gametype' ";

  switch($type) {
    case "squad"     : $gettype = "GROUP BY $players_table.squad HAVING $players_table.squad = '$id'"   ; break;
	case "player"    : $gettype = "GROUP BY $players_table.id HAVING $players_table.id = '$id'"         ; break;
  }
  
  switch($value) {
    case "assault"   : $wpntype = "'CAR15', 'CAR15/M203', 'M16', 'M16/M203', 'G3', 'G36', 'MP5'"                                      ; break;
    case "support"   : $wpntype = "'M60', 'M240', 'M249 SAW'"                                                                         ; break;
    case "sniper"    : $wpntype = "'PSG1', 'MCRT .300 Tactical', 'M21', 'M24', 'Barrett .50 Cal'"                                     ; break;
    case "secondary" : $wpntype = "'Colt .45', 'M9 Beretta', 'Remington Shotgun'"                                                     ; break;
    case "other"     : $wpntype = "'Knife', 'CAR15/M203 - Grenade', 'M16/M203 - Grenade', 'Frag Grenade', 'Claymore', 'AT4'"          ; break;
    case "emplace"   : $wpntype = "'50 Cal Humvee', 'MiniGun', 'Grenade Launcher', '50 Cal Emplacement', 'E M Canon', '50 Cal Truck'" ; break;
  }
	
  $query = query("SELECT $players_table.id, $players_table.squad, $stats_table.id, $stats_table.player, 
                      $weapons_table.id, $weapons_table.name, $weaponstats_table.record, $weaponstats_table.weapon, SUM( $weaponstats_table.kills ) AS kills  
                    FROM $players_table, $stats_table, $weapons_table, $weaponstats_table WHERE $weapons_table.id = $weaponstats_table.weapon 
					AND $weaponstats_table.record = $stats_table.id AND $stats_table.player = $players_table.id
                    AND $weapons_table.name IN ($wpntype) 
					$conditions 
                    $gettype");
  while ($row = fetch_array($query)) {
    $kills = $row["kills"];
  }
  $ttlkills = query("SELECT $players_table.id, $players_table.squad, $stats_table.player, 
                         SUM($stats_table.kills) AS kills 
					   FROM $players_table, $stats_table WHERE $stats_table.player = $players_table.id 
					   AND $players_table.squad = '$id' $conditions GROUP BY $players_table.squad");
  while ($ttl_row = fetch_array($ttlkills)) {
    $tkills = $ttl_row["kills"];
    $div = ($tkills+1);
    $result = round ($kills/$div, 1);
  }
  return $result;
}

function GetMapWeaponUsage($value, $id, $server) {
  global $bmt_pre, $stats_table, $weaponstats_table, $weapons_table, $mapstats_table, $maps_table;
 
  if($server   != -1)    $conditions .= " AND $stats_table.server='$server' ";
  
  switch($value) {
    case "assault"   : $wpntype = "'CAR15', 'CAR15/M203', 'M16', 'M16/M203', 'G3', 'G36', 'MP5'"                                      ; break;
    case "support"   : $wpntype = "'M60', 'M240', 'M249 SAW'"                                                                         ; break;
    case "sniper"    : $wpntype = "'PSG1', 'MCRT .300 Tactical', 'M21', 'M24', 'Barrett .50 Cal'"                                     ; break;
    case "secondary" : $wpntype = "'Colt .45', 'M9 Beretta', 'Remington Shotgun'"                                                     ; break;
    case "other"     : $wpntype = "'Knife', 'CAR15/M203 - Grenade', 'M16/M203 - Grenade', 'Frag Grenade', 'Claymore', 'AT4'"          ; break;
    case "emplace"   : $wpntype = "'50 Cal Humvee', 'MiniGun', 'Grenade Launcher', '50 Cal Emplacement', 'E M Canon', '50 Cal Truck'" ; break;
  }
	
  $query = query("SELECT $maps_table.id, $mapstats_table.map, $mapstats_table.record, $weaponstats_table.record, 
                      $weaponstats_table.weapon, SUM( $weaponstats_table.kills ) AS kills, 
					  $weapons_table.*, $stats_table.id, $stats_table.server
                    FROM $maps_table, $mapstats_table, $weaponstats_table, $weapons_table, $stats_table
                    WHERE $maps_table.id = '$id' AND $mapstats_table.map = $maps_table.id
                    AND $mapstats_table.record = $weaponstats_table.record AND $weaponstats_table.record = $stats_table.id 
                    AND $weaponstats_table.weapon = $weapons_table.id AND $weapons_table.name IN ($wpntype)
                    $conditions GROUP BY $maps_table.id");
  while ($row = fetch_array($query)) {
    if(!($row["kills"])){$kills = 0;} else {$kills = $row["kills"];}
  }
  $ttlkills = query("SELECT $maps_table.id, $mapstats_table.map, $mapstats_table.record, $weaponstats_table.record, 
                        $weaponstats_table.weapon, SUM( $weaponstats_table.kills ) AS kills, 
					    $stats_table.id, $stats_table.server
                      FROM $maps_table, $mapstats_table, $weaponstats_table, $stats_table
                      WHERE $maps_table.id = '$id' AND $mapstats_table.map = $maps_table.id
                      AND $mapstats_table.record = $weaponstats_table.record AND $weaponstats_table.record = $stats_table.id 
                      $conditions GROUP BY $maps_table.id");
  while ($ttl_row = fetch_array($ttlkills)) {
    if(!($ttl_row["kills"])){
	  $result = 0;
	} else {
      $tkills = $ttl_row["kills"];
      $div = ($tkills/100);
      $result = round ($kills/$div, 0);
	}
  }
  return $result;
}

function GetRatioColor($ratio) {
  if($ratio < '1.00' AND $ratio > '0.00') {
    $class = "red";
  } elseif($ratio > '1.00') {
    $class = "green";
  } elseif($ratio === '0.00') {
    $class = "blue";
  } else {
    $class = "blue";
  }
  return $class;
}
   
function GetPlayers1List($current, $all, $query_str) {
  $query           = query($query_str);
  $players1         = array();
  $row             = array();
  $count           = 0;
  if($all != 0) {
    $row["name"]     = "Select Player";
    $row["id"]       = -1;
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $players1[$count] = $row;
    $count++;
  }
  while($row = fetch_array($query)) {
  $row["name"] = htmlspecialchars(base64_decode(str_replace(" ", "+", $row["name"])));
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $players1[$count] = $row;
    $count++;
  }
  return $players1;
}

function GetPlayers2List($current, $all, $query_str) {
  $query           = query($query_str);
  $players2         = array();
  $row             = array();
  $count           = 0;
  if($all != 0) {
    $row["name"]     = "Select Player";
    $row["id"]       = -1;
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $players2[$count] = $row;
    $count++;
  }
  while($row = fetch_array($query)) {
  $row["name"] = htmlspecialchars(base64_decode(str_replace(" ", "+", $row["name"])));
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $players2[$count] = $row;
    $count++;
  }
  return $players2;
}

function GetSquadsList($current, $all) {
  global $bmt_pre, $squads_table;
  $query          = query("SELECT * FROM $squads_table ORDER BY name");
  $squads         = array();
  $row            = array();
  $count          = 0;
  if($all != 0) {
    $row["name"]    = "All";
    $row["id"]      = -1;
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $squads[$count] = $row;
    $count++;
  }
  while($row = fetch_array($query)) {
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $squads[$count] = $row;
    $count++;
  }
  return $squads;
}

function GetServersList($current, $all, $query_str) {
  $query           = query($query_str);
  $servers         = array();
  $row             = array();
  $count           = 0;
  if($all != 0) {
    $row["name"]     = "All";
    $row["id"]       = -1;
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $servers[$count] = $row;
    $count++;
  }
  while($row = fetch_array($query)) {
    if($current == $row["id"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $servers[$count] = $row;
    $count++;
  }
  return $servers;
}

function GetGameTypesList($current, $all, $query_str) {
  $query             = query($query_str);
  $gametypes         = array();
  $row               = array();
  $count             = 0;
  if($all != 0) {
    $row["name"]       = "All";
    $row["game_type"]  = "All";
    if($current == $row["game_type"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $gametypes[$count] = $row;
    $count++;
  }
  while($row = fetch_array($query)) {
    if($current == $row["game_type"]) {
      $row["selected"] = "selected=\"selected\"";
    } else {
      $row["selected"] = "";
    }
    $row["name"] = $row["game_type"];
    $gametypes[$count] = $row;
    $count++;
  }
  return $gametypes;
}

function GetRank($rating) {
  global $bmt_pre, $players_table, $ranks_table;
  if($rating < 0) $rating = 0;
  $max_rank_query   = query("SELECT * FROM $ranks_table ORDER BY rating DESC LIMIT 1");
  $max_rank         = fetch_array($max_rank_query);
  $max_rank         = $max_rank["id"];
  $rank_query       = query("SELECT * FROM $ranks_table WHERE rating<='$rating' ORDER BY rating DESC");
  $rank             = fetch_array($rank_query);
  if($max_rank == $rank["id"]) {
    $max_rating_query = query("SELECT * FROM $players_table ORDER BY rating DESC LIMIT 1");
    $max_rating       = fetch_array($max_rating_query);
    $max_rating       = $max_rating["rating"];
    if($max_rating > $rating) {
      $rank = fetch_array($rank_query);
    }
  }
  return $rank;
}

function ShortenGameType($gametype) {
  switch($gametype) {
    case "Cooperative"           : $gametype = "COOP";   break;
    case "Deathmatch"            : $gametype = "DM";     break;
    case "Team Deathmatch"       : $gametype = "TDM";    break;
    case "King of the Hill"      : $gametype = "KOTH";   break;
    case "Team King of the Hill" : $gametype = "TKOTH";  break;
    case "Capture the Flag"      : $gametype = "CTF";    break;
    case "Flagball"              : $gametype = "FB";     break;
    case "Attack and Defend"     : $gametype = "A&D";    break;
    case "Search and Destroy"    : $gametype = "S&D";    break;
    default                      : $gametype = "unknown";
  }
  return $gametype;  
}

function ShortenGameName($gamename) {
  $gamename = str_replace("Delta Force Land Warrior",      "DFLW",  $gamename);
  $gamename = str_replace("Delta Force Task Force Dagger", "DFTFD", $gamename);
  $gamename = str_replace("Delta Force Black Hawk Down",   "DFBHD", $gamename);
  $gamename = str_replace("Delta Force 2",                 "DF2",   $gamename);
  $gamename = str_replace("Delta Force",                   "DF",    $gamename);
  return $gamename;
}

function ParseMessage($msg, $tpl, $t, $s, &$target, &$source) {
  $tpl   = "-".$tpl."-";
  $msg   = "-".$msg."-";
  $parts = explode("$", $tpl);
  $num   = count($parts);
  $part  = $msg;
  $found = array();
  $index = 1;
  for($i = 0; $i < $num; $i++) {
    $old_part = $part;
    $part = strstr($part, $parts[$i]);
    if(!$part) return FALSE;
    $parti = substr($part, 0, strlen($parts[$i]));
    if($i != 0) {
      $ply = substr($old_part, strlen($parts[$i-1]), strlen($old_part) - strlen($part) - strlen($parts[$i-1]));
      $found[$index] = $ply;
      $index++;     
    }
  }
  $target = $found[$t];
  $source = $found[$s];
  return TRUE;
}

function formatRow($id, $table) {

  $query = mysql_query("select * from $table WHERE id = '$id'");
  $num_fields = @mysql_num_fields($query);

  $row = @mysql_fetch_row($query);
  $string = "INSERT INTO $table VALUES(";
  for($i = 0; $i < $num_fields; $i++) {
    if($i < ($num_fields-1)) {
	  $string .= '"'.$row[$i].'", ';
	} else {
	  $string .= '"'.$row[$i].'"';
    }
  }
  $string .= ");\r\n";
  return $string;
}








?>