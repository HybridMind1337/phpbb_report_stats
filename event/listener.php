<?php
namespace hybridmind\user_reports_stats\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    
    /** @var \phpbb\config\config */
    protected $config;
    
    /** @var \phpbb\template\template */
    protected $template;
    
    /** @var \phpbb\user */
    protected $user;
    
    protected $db;
    
    protected $helper;
    
    public function __construct(\phpbb\config\config $config, \phpbb\template\template $template, \phpbb\user $user, \phpbb\db\driver\driver_interface $db, \phpbb\controller\helper $helper) 
    {
        $this->config   = $config;
        $this->template = $template;
        $this->user     = $user;
        $this->db       = $db;
        $this->helper   = $helper; //for route
    }
    
    
    static public function getSubscribedEvents() {
        return array(
            'core.page_footer' => 'check_pieces',
            'core.memberlist_view_profile' => 'show_pieces',
            'core.report_post_auth' => 'stats'
        );
    }
    
    
    public function check_pieces($event) {
        $sql1    = 'SELECT COUNT(report_id) as pieces from phpbb_reports WHERE report_closed=0';
        $result1 = $this->db->sql_query($sql1);
        $rowz    = $this->db->sql_fetchrow($result1);
        $this->db->sql_freeresult($result1);
        $reports_pieces = $rowz['pieces'];
        $this->template->assign_vars(array(
            'REPORTS_NUM' => $reports_pieces
        ));
        
    }
    public function show_pieces($event) {
        $userid = $event['member']['user_id'];
        
        $sql1    = 'SELECT COUNT(report_id) as pieces FROM phpbb_reports WHERE user_id=' . $userid . ' AND report_closed=1';
        $result1 = $this->db->sql_query($sql1);
        $rowz    = $this->db->sql_fetchrow($result1);
        $this->db->sql_freeresult($result1);
        $reports_pieces = $rowz['pieces'];
        $this->template->assign_vars(array(
            'CLOSED_REPORTS' => $reports_pieces
        ));
    }
    
    public function stats($event) {
        global $user, $request;
        if ($request->is_set_post('submit')) {
            $userid = $user->data['user_id'];
            
            $sql1    = 'SHOW TABLE STATUS LIKE "phpbb_reports"';
            $result1 = $this->db->sql_query($sql1);
            $rowz    = $this->db->sql_fetchrow($result1);
            $this->db->sql_freeresult($result1);
            $max_id = $rowz['Auto_increment'];
            
            $checker_for_double_content = $this->db->sql_query('SELECT report_id FROM phpbb_reports_saver WHERE report_id=' . $max_id . '');
            $row                        = $this->db->sql_fetchrowset($checker_for_double_content);
            $count                      = sizeof($row);
            $this->db->sql_freeresult($checker_for_double_content);
            if ($count == 0) {
                $sql1    = 'INSERT INTO phpbb_reports_saver (user_id,report_id) VALUES(' . $userid . ', ' . $max_id . ')';
                $result1 = $this->db->sql_query($sql1);
                $this->db->sql_freeresult($result1);
            }
        }
    }
    
}


