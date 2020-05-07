/*
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */

var default_image = CKEDITOR.getUrl(CKEDITOR.plugins.getPath('templates')) + 'templates/generic.png';
// Register a templates definition set named "default".
CKEDITOR.addTemplates( 'default', {
	// The name of sub folder which hold the shortcut preview images of the
	// templates.
	imagesPath: CKEDITOR.getUrl(CKEDITOR.plugins.getPath('templates') + 'templates/images/'),
	// The templates definitions.
	templates: [ {
		title: 'Left Column Template',
		image: 'leftColumn.gif',
		description: 'One main image with a title and text that surround the image.',
		html: 
			'<div class="row two-col-left">'+
				'<div class="col-md-3 col-sidebar">'+
					'<p>'+
						'<img alt="Winter Image" data-entity-type="file" data-entity-uuid="975b7a57-1a51-4e57-80d9-f570e6b72f3c" src='+default_image+'>'+
					'</p>'+
				'</div>'+
				'<div class ="col-md-9 col-main">'+
					'<h2>Left Column Template</h2>'+
						'<p>'+
							'This is the left column template.  Image can be deleted on left-hand side to put text instead if you wanted.'+
						'</p>'+
						'<p>'+
							'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
						'</p>'+
				'</div>'+
			'</div>'
	},
	{
		title: 'Right Column Template',
		image: 'rightColumn.gif',
		description: 'One main image with a title and text that surround the image.',
		html: 
		'<div class="row two-col-right">'+
				'<div class ="col-md-9 col-main">'+
					'<h2>Right Column Template</h2>'+
						'<p>'+
							'This is the Right column template.  Image can be deleted on right-hand side to put text instead if you wanted.'+
						'</p>'+
						'<p>'+
							'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
						'</p>'+
				'</div>'+
				'<div class="col-md-3 col-sidebar">'+
					'<p>'+
						'<img alt="Winter Image" data-entity-type="file" data-entity-uuid="975b7a57-1a51-4e57-80d9-f570e6b72f3c" src='+default_image+'>'+
					'</p>'+
				'</div>'+
			'</div>'
	},
	{
		title: 'Two Column Template',
		image: 'twoColumn.gif',
		description: 'Two Columns of text',
		html:
		'<div class="row two-col">'+
			'<div class="col-md-6 col-1">'+
				'<h3>Two Column Template</h3>'+
				'<p>Can have images or text</p>'+
				'<p>'+
					'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
				'</p>'+ 
			'</div>'+
			'<div class="col-md-6 col-2">'+
				'<h3>Two Column Template</h3>'+
				'<p>Can have images or text</p>'+
				'<p>'+
					'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
				'</p>'+ 
			'</div>'+
		'</div>'
		}, 
	{
		title: 'Three Column Template',
		image: 'threeColumn.gif',
		description: 'Three Columns of text',
		html:
		'<div class="row three-col">'+
			'<div class="col-md-4 col-1">'+
				'<h3>Three Column Template</h3>'+
				'<p>Can have images or text</p>'+
				'<p>'+
					'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
				'</p>'+ 
			'</div>'+
			'<div class="col-md-4 col-2">'+
				'<h3>Three Column Template</h3>'+
				'<p>Can have images or text</p>'+
				'<p>'+
					'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
				'</p>'+ 
			'</div>'+
			'<div class="col-md-4 col-3">'+
				'<h3>Three Column Template</h3>'+
				'<p>Can have images or text</p>'+
				'<p>'+
					'Could use it as a call to action and make it really stand out, or it could be used just for easier editing for our content editors, without them having to do any padding or anything.  Also need to verify that this will work with the image resize filter, so that the image is resized upon insertion.  Will need to test as well with the media module if we decide to use that.'+
				'</p>'+ 
			'</div>'+
		'</div>'
		},
    {title:'Two Columns',
    image:'two-columns.gif',
    description:'Div container with two columns.',
    html:'<p>Text before two columns.</p><div class="row two-columns"><div class="l-span-B6"><p>Col 1</p></div><div class="l-span-B6"><p>Col 2</p></div></div><p>Text after two columns.</p>'
    }
]
} );
