<?php
if(class_exists('Widget')) {

	/**
	 * Blog Management Widget
	 * @package blog
	 */
class BlogManagementWidget extends Widget {
	private static $db = array();

	private static $has_one = array();

	private static $has_many = array();

	private static $many_many = array();

	private static $belongs_many_many = array();

	private static $defaults = array();

	private static $title = "Blog Management";
	private static $cmsTitle = "Blog Management";
	private static $description = "Provide a number of links useful for administering a blog. Only shown if the user is an admin.";

		function CommentText() {

			if(!class_exists('Comment')) return false;
			$unmoderatedcount = DB::query("SELECT COUNT(*) FROM \"Comment\" WHERE \"Moderated\"=1")->value();
			if($unmoderatedcount == 1) {
				return _t("BlogManagementWidget.UNM1", "You have 1 unmoderated comment");
			} else if($unmoderatedcount > 1) {
				return sprintf(_t("BlogManagementWidget.UNMM", "You have %i unmoderated comments"), $unmoderatedcount);
			} else {
				return _t("BlogManagementWidget.COMADM", "Comment administration");
			}
		}

		function CommentLink() {

			if(!Permission::check('BLOGMANAGEMENT') || !class_exists('Comment')) return false;
			$unmoderatedcount = DB::query("SELECT COUNT(*) FROM \"Comment\" WHERE \"Moderated\"=1")->value();

			if($unmoderatedcount > 0) {
				return "admin/comments/unmoderated";
			} else {
				return "admin/comments";
			}
		}

	}

	class BlogManagementWidget_Controller extends Widget_Controller { 
		
		function WidgetHolder() { 
			if(Permission::check("BLOGMANAGEMENT")) { 
				return $this->renderWith("WidgetHolder"); 
			} 
		}
		
		function PostLink() {
			$container = BlogTree::current();
			return ($container && $container->ClassName != "BlogTree") ? $container->Link('post') : false; 
		}
	}

}