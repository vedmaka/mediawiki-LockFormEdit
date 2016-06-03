<?php

class LockFormEditHooks {
    
    public static function onMediaWikiPerformAction( $output, $article, $title, $user, $request, $wiki )
    {
        
        global $wgLockFormEditCategories;
        
        // Skin check for sysop user
        if( $user && !$user->isAnon() && in_array( 'sysop', $user->getGroups() ) ) {
            return true;
        }
        
        // Skip check if title not exists
        if( !$title || !$title->exists() ) {
            return true;
        }
        
        // Skip check if it is not in specified category
        $categories = SFUtils::getCategoriesForPage( $title );
        if( !count( array_intersect( $wgLockFormEditCategories, $categories ) ) ) {
            return true;
        }
        
        // Prevent users for edit
        if( $request && $request->getCheck('action') && $request->getVal('action') == 'edit' )
        {
            $output->setPageTitle( wfMessage('lockformedit-error-title')->plain() );
            $output->addWikiText( wfMessage('lockformedit-error-text')->plain() );
            return false;
        }
    }
    
}