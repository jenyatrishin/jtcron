<?php
namespace JtCron\Controller\Rest;

use JtCron\Model\Cronjob;

/**
 * Class CronjobController
 * @package JtCron\Controller\Rest
 */
class CronjobController extends \WP_REST_Controller
{
    /**
     * @var Cronjob
     */
    protected $_cronjob;

    /**
     * @var string
     */
    protected $_namespace;

    /**
     * CronjobController constructor.
     */
    public function __construct()
    {
        $this->_cronjob = new Cronjob();
        $this->_namespace = 'v2/jtcron';
    }

    /**
     * @return void
     */
    public function register_routes() : void
    {
        register_rest_route( $this->_namespace, '/cronjob', array(
            array(
                'methods'  => \WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_items' )
            ),
        ) );

        register_rest_route( $this->_namespace, '/cronjob/(?P<id>[\d]+)', array(
            array(
                'methods'  => \WP_REST_Server::READABLE,
                'callback' => array( $this, 'get_item' )
            ),
        ) );
		
		register_rest_route( $this->_namespace, '/cronjob', array(
            array(
                'methods'  => \WP_REST_Server::DELETABLE,
                'callback' => array( $this, 'delete_items' )
            ),
        ) );
    }

    /**
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function get_items( $request ) : \WP_REST_Response 
	{
        $data = [];
        $records = $this->_cronjob->getList([]);
        if (count($records)) {
            foreach ($records as $record) {
                $data[] = $record->getData();
            }
        }
        return new \WP_REST_Response($data, 200);
    }

    /**
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
    public function get_item($request) : \WP_REST_Response
    {
        $item = $this->_cronjob->find($request->get_param('id'));
        return new \WP_REST_Response($item->getData(), 200);
    }
	
	/**
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response
     */
	public function delete_items($request) : \WP_REST_Response
	{
		try {
			$this->_cronjob->getEntityInstance()->delete(implode(',',$request->get_param('records')));
			$data = [
				'result' => 'ok'
			];		
		} catch (\Exception $e) {
			$data = [
				'result' => 'error',
				'message' => $e->getMessage()
			];
		}
		return new \WP_REST_Response($data, 200);
	}
}