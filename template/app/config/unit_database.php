<?php
$unit_database = TSession::getValue('SGPT_DB');
return TConnection::getDatabaseInfo( $SGPT_DB );
