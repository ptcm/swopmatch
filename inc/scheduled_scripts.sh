#!/bin/sh

/usr/local/bin/php /home/swopmatc/public_html/inc/match_loc_to_loc.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_loc_to_town.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_loc_to_distr.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_town_to_town.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_distr_to_town.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_distr_to_distr.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_province_to_loc.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_province_to_town.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_province_to_distr.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/match_province_to_province.php
wait

/usr/local/bin/php /home/swopmatc/public_html/inc/receipting.php

/usr/local/bin/php /home/swopmatc/public_html/inc/client_finalization.php
# wait


echo all done


