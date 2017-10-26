<?php
class WebUser extends CWebUser
{
    /**
     * Overrides a Yii method that is used for roles in controllers (accessRules).
     *
     * @param string $operation Name of the operation required (here, a role).
     * @param mixed $params (opt) Parameters for this operation, usually the object to access.
     * @return bool Permission granted?
     */
    public function checkAccess($operation, $params=array())
    {
        //var_dump(Yii::app()->getId());exit;
        if ( ( is_array( $operation ) && in_array( 'admin', $operation ) ) || $operation === 'admin' )
            Yii::app()->user->loginUrl = array( '/admins/login' );
        else
            Yii::app()->user->loginUrl = array( '/login' );
        if ( empty( $this->id ) ) {
            // Not identified => no rights
            return false;
        }

        $role = $this->getState( "roles" );

        if ( $role === 'admin' ) {
            return true; // admin role has access to everything
        }
        if ( is_array( $operation ) ) { // Check if multiple roles are available
            return ( array_search( $role, $operation ) !== false );
        }
        // allow access if the operation request is the current user's role
        return ( $operation === $role );
    }
}