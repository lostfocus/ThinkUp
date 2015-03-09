<?php
/*
 Plugin Name: My New Plugin
 Description: This plugin does amazing things
 */
/**
 *
 * ThinkUp/webapp/plugins/insightsgenerator/insights/geomap.php
 *
 * LICENSE:
 *
 * This file is part of ThinkUp (http://thinkup.com).
 *
 * ThinkUp is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThinkUp is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with ThinkUp.  If not, see
 * <http://www.gnu.org/licenses/>.
 *
 *
 * GeoMap
 *
 * Description of what this class does
 *
 * Copyright (c) 2013 Dominik Schwind
 *
 * @author Dominik Schwind dschwind@lostfocus.de
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2013 Dominik Schwind
 */

class GeoMapInsight extends InsightPluginParent implements InsightPlugin {

	var $slug = 'geomap';

    public function generateInsight(Instance $instance, User $user, $last_week_of_posts, $number_days) {
        parent::generateInsight($instance, $user, $last_week_of_posts, $number_days);

        $this->logger->logInfo("Begin generating insight", __METHOD__.','.__LINE__);
        $filename = basename(__FILE__, ".php");

		$geos = array();
        foreach ($last_week_of_posts as $post) {
			if($post->geo){
				$date = date('Y-m-d', strtotime($post->pub_date));
				if(!isset($geos[$date])){
					$geos[$date] = array();
				}
				if(!in_array($post->place,$geos[$date])){
					$geos[$date][] = $post->place;
				}
			}
        }
		if(count($geos) > 0){
			foreach($geos as $date => $places){
				$place = false;
				if(count($places) == 1){
					$place = $places[0];
				} elseif(count($places) > 2){
					$lastplace = array_pop($places);
					$place = implode(", ",$places);
					$place .= " and ".$lastplace;
				} elseif(count($places) == 2){
					$place = implode(" and ",$places);
				}
				if($place){
					$geo_insight = new Insight();
					$geo_insight->instance_id = $instance->id;
					$geo_insight->slug = $this->slug;
					$geo_insight->headline = (count($places) > 1) ? "Places today" : "Place today";
					$geo_insight->text = "Today you have been to " . $place . ".";
					$geo_insight->date = $date;
					$geo_insight->emphasis = Insight::EMPHASIS_MED;
					$geo_insight->filename = $filename;
					$this->insight_dao->insertInsight($geo_insight);
					$geo_insight = null;
 				}
			}
		}
        $this->logger->logInfo("Done generating insight", __METHOD__.','.__LINE__);
    }
}

$insights_plugin_registrar = PluginRegistrarInsights::getInstance();
$insights_plugin_registrar->registerInsightPlugin('GeoMapInsight');

