<?php
/*
 Plugin Name: My New Plugin
 Description: This plugin does amazing things
 */
/**
 *
 * ThinkUp/webapp/plugins/insightsgenerator/insights/inactiveuser.php
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
 * InactiveUser (name of file)
 *
 * Description of what this class does
 *
 * Copyright (c) 2013 Dominik Schwind
 *
 * @author Dominik Schwind dschwind@lostfocus.de
 * @license http://www.gnu.org/licenses/gpl.html
 * @copyright 2013 Dominik Schwind
 */

class InactiveUserInsight extends InsightPluginParent implements InsightPlugin {

    public function generateInsight(Instance $instance, User $user, $last_week_of_posts, $number_days) {
        parent::generateInsight($instance, $user, $last_week_of_posts, $number_days);

        $this->logger->logInfo("Begin generating insight", __METHOD__.','.__LINE__);
        $filename = basename(__FILE__, ".php");

        /*foreach ($last_week_of_posts as $post) {
            $my_insight = new Insight();
            $my_insight->instance_id = $instance->id;
            $my_insight->slug = 'insight_name_goes_here'.$post->id;
            $my_insight->date = date('Y-m-d', strtotime($post->pub_date));
            $my_insight->headline = 'Snappy headline';
            $my_insight->text = 'Body of the insight';
            $my_insight->emphasis = Insight::EMPHASIS_MED;
            $my_insight->filename = $filename;

            //OPTIONAL: Attach related data of various types using Insight setter functions
            //$my_insight->setPosts($my_insight_posts);
            //$my_insight->setLinks($my_insight_links);
            //$my_insight->setPeople($my_insight_people);
            //etc

			var_dump($my_insight);

            $this->insight_dao->insertInsight($my_insight);
            $my_insight = null;
        }*/
		if ($instance->network == 'twitter') {
			$follow_dao = DAOFactory::getDAO('FollowDAO');
			$staleFriends = $follow_dao->getStalestFriends($instance->network_user_id,
				$instance->network,2);
			var_dump($staleFriends);
		}
        $this->logger->logInfo("Done generating insight", __METHOD__.','.__LINE__);
    }
}

$insights_plugin_registrar = PluginRegistrarInsights::getInstance();
$insights_plugin_registrar->registerInsightPlugin('InactiveUserInsight');

