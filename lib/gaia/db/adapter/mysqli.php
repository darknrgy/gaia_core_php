<?php
namespace Gaia\DB;

$db = $this->core();

$_ = array();

$_['__tostring'] = function() use ( $db ) {
    @ $res ='(Gaia\DB\MySQLi object - ' . "\n" .
        '  [affected_rows] => ' . $db->affected_rows . "\n" .
        '  [client_info] => ' . $db->client_info . "\n" .
        '  [client_version] => ' . $db->client_version . "\n" .
        '  [connect_errno] => ' . $db->connect_errno . "\n" .
        '  [connect_error] => ' . $db->connect_error . "\n" .
        '  [errno] => ' . $db->errno . "\n" .
        '  [error] => ' . $db->error . "\n" .
        '  [field_count] => ' . $db->field_count . "\n" .
        '  [host_info] => ' . $db->host_info . "\n" .
        '  [info] => ' . $db->info . "\n" .
        '  [insert_id] => ' . $db->insert_id . "\n" .
        '  [server_info] => ' . $db->server_info . "\n" .
        '  [server_version] => ' . $db->server_version . "\n" .
        '  [sqlstate] => ' . $db->sqlstate . "\n" .
        '  [protocol_version] => ' . $db->protocol_version . "\n" .
        '  [thread_id] => ' . $db->thread_id . "\n" .
        '  [warning_count] => ' . $db->warning_count . "\n" .
        ')';
    return $res;
};

$_['format_query_args'] = $format_args = function($query, array $args ) use ( $db ){
    if( ! $args || count( $args ) < 1 ) return $query;
    return \Gaia\DB\Query::format( 
        $query, 
        $args, 
        function($v) use( $db ){ return "'" . $db->real_escape_string( $v ) . "'"; }
       );

};
        
$_['execute'] = function( $query ) use ( $db ){
    $res = $db->query( $query );
    if( ! $res ) return FALSE;
    $_ = array();
    
    if( is_object( $res ) ){
        $_['fetch'] = function() use( $res ){
            return $res->fetch_assoc();
        };
        $_['free'] = function() use( $res ){
            $res->free_result();
        };
    }
    $_['affected'] = $db->affected_rows;
    $_['insertid'] = $db->insert_id;
    
    return new Result( $_ );
};
            
$_['start'] = function ($auth = NULL) use ( $db ){
    if( $db instanceof Iface ) return $db->start($auth);
    return $db->query('START TRANSACTION');
};

$_['rollback'] = function ($auth = NULL) use ( $db ){
    if( $db instanceof Iface ) return $db->rollback($auth);
    return $db->query('ROLLBACK');
};

$_['commit'] = function ($auth = NULL) use ( $db ){
    if( $db instanceof Iface ) return $db->commit($auth);
    return $db->query('COMMIT');
};

$_['error'] = function() use ( $db ){
    return $db->error;
};

$_['errorcode'] = function() use ( $db ){
    return $db->errno;
};

$_['isa'] = function($name) use ( $db ){
    if( $db instanceof $name) return TRUE;
    if( $name == 'mysql' ) return TRUE;
    return FALSE;
};

return $_;