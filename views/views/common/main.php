<div id="page-content-wrapper">
            <div id="page-content" style="min-height: 1049px;">

                    <div class="container">


<!-- Bootstrap Modal -->

<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/modal/modal.css">-->
<!--<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/modal/modal.js"></script>-->

<!-- JS Interactions -->

<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/interactions-ui/resizable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/interactions-ui/draggable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/interactions-ui/sortable.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/interactions-ui/selectable.js"></script>

<!-- jQueryUI Dialog -->

<!--<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/widgets/dialog/dialog.css">-->
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/dialog/dialog.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>assets/widgets/dialog/dialog-demo.js"></script>

<div id="page-title">
    <h2>Modals</h2>
    <p>Modals are streamlined, but flexible, dialog prompts.</p>
    <div id="theme-options" class="admin-options">
    <a href="javascript:void(0);" class="btn btn-primary theme-switcher tooltip-button" data-placement="left" title="" data-original-title="Color schemes and layout options">
        <i class="glyph-icon icon-linecons-cog icon-spin"></i>
    </a>
    <div id="theme-switcher-wrapper" style="height: 304.286px;">
        <div class="slimScrollDiv" style="position: relative; overflow: hidden; width: auto; height: 100%;"><div class="scroll-switcher" style="overflow: hidden; width: auto; height: 100%;">
            <h5 class="header">Layout options</h5>
            <ul class="reset-ul">
                <li>
                    <label for="boxed-layout">Boxed layout</label>
                    <input type="checkbox" data-toggletarget="boxed-layout" id="boxed-layout" class="input-switch-alt" style="display: none;"><div id="#boxed-layout" class="switch-toggle" style="display: block;"></div>
                </li>
                <li>
                    <label for="fixed-header">Fixed header</label>
                    <input type="checkbox" data-toggletarget="fixed-header" id="fixed-header" class="input-switch-alt" style="display: none;"><div id="#fixed-header" class="switch-toggle" style="display: block;"></div>
                </li>
                <li>
                    <label for="fixed-sidebar">Fixed sidebar</label>
                    <input type="checkbox" data-toggletarget="fixed-sidebar" id="fixed-sidebar" class="input-switch-alt" style="display: none;"><div id="#fixed-sidebar" class="switch-toggle" style="display: block;"></div>
                </li>
                <li>
                    <label for="closed-sidebar">Closed sidebar</label>
                    <input type="checkbox" data-toggletarget="closed-sidebar" id="closed-sidebar" class="input-switch-alt" style="display: none;"><div id="#closed-sidebar" class="switch-toggle" style="display: block;"></div>
                </li>
            </ul>
            <div class="boxed-bg-wrapper hide">
                <h5 class="header">
                    Boxed Page Background
                    <a class="set-background-style" data-header-bg="" title="Remove all styles" href="javascript:void(0);">Clear</a>
                </h5>
                <div class="theme-color-wrapper clearfix">
                    <h5>Patterns</h5>
                    <a class="tooltip-button set-background-style pattern-bg-3" data-header-bg="pattern-bg-3" title="" href="javascript:void(0);" data-original-title="Pattern 3">Pattern 3</a>
                    <a class="tooltip-button set-background-style pattern-bg-4" data-header-bg="pattern-bg-4" title="" href="javascript:void(0);" data-original-title="Pattern 4">Pattern 4</a>
                    <a class="tooltip-button set-background-style pattern-bg-5" data-header-bg="pattern-bg-5" title="" href="javascript:void(0);" data-original-title="Pattern 5">Pattern 5</a>
                    <a class="tooltip-button set-background-style pattern-bg-6" data-header-bg="pattern-bg-6" title="" href="javascript:void(0);" data-original-title="Pattern 6">Pattern 6</a>
                    <a class="tooltip-button set-background-style pattern-bg-7" data-header-bg="pattern-bg-7" title="" href="javascript:void(0);" data-original-title="Pattern 7">Pattern 7</a>
                    <a class="tooltip-button set-background-style pattern-bg-8" data-header-bg="pattern-bg-8" title="" href="javascript:void(0);" data-original-title="Pattern 8">Pattern 8</a>
                    <a class="tooltip-button set-background-style pattern-bg-9" data-header-bg="pattern-bg-9" title="" href="javascript:void(0);" data-original-title="Pattern 9">Pattern 9</a>
                    <a class="tooltip-button set-background-style pattern-bg-10" data-header-bg="pattern-bg-10" title="" href="javascript:void(0);" data-original-title="Pattern 10">Pattern 10</a>

                    <div class="clear"></div>

                    <h5 class="mrg15T">Solids &amp;Images</h5>
                    <a class="tooltip-button set-background-style bg-black" data-header-bg="bg-black" title="" href="javascript:void(0);" data-original-title="Black">Black</a>
                    <a class="tooltip-button set-background-style bg-gray mrg10R" data-header-bg="bg-gray" title="" href="javascript:void(0);" data-original-title="Gray">Gray</a>

                    <a class="tooltip-button set-background-style full-bg-1" data-header-bg="full-bg-1 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 1">Image 1</a>
                    <a class="tooltip-button set-background-style full-bg-2" data-header-bg="full-bg-2 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 2">Image 2</a>
                    <a class="tooltip-button set-background-style full-bg-3" data-header-bg="full-bg-3 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 3">Image 3</a>
                    <a class="tooltip-button set-background-style full-bg-4" data-header-bg="full-bg-4 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 4">Image 4</a>
                    <a class="tooltip-button set-background-style full-bg-5" data-header-bg="full-bg-5 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 5">Image 5</a>
                    <a class="tooltip-button set-background-style full-bg-6" data-header-bg="full-bg-6 fixed-bg" title="" href="javascript:void(0);" data-original-title="Image 6">Image 6</a>

                </div>
            </div>
            <h5 class="header">
                Header Style
                <a class="set-adminheader-style" data-header-bg="bg-gradient-9" title="Remove all styles" href="javascript:void(0);">Clear</a>
            </h5>
            <div class="theme-color-wrapper clearfix">
                <h5>Solids</h5>
                <a class="tooltip-button set-adminheader-style bg-primary" data-header-bg="bg-primary font-inverse" title="" href="javascript:void(0);" data-original-title="Primary">Primary</a>
                <a class="tooltip-button set-adminheader-style bg-green" data-header-bg="bg-green font-inverse" title="" href="javascript:void(0);" data-original-title="Green">Green</a>
                <a class="tooltip-button set-adminheader-style bg-red" data-header-bg="bg-red font-inverse" title="" href="javascript:void(0);" data-original-title="Red">Red</a>
                <a class="tooltip-button set-adminheader-style bg-blue" data-header-bg="bg-blue font-inverse" title="" href="javascript:void(0);" data-original-title="Blue">Blue</a>
                <a class="tooltip-button set-adminheader-style bg-warning" data-header-bg="bg-warning font-inverse" title="" href="javascript:void(0);" data-original-title="Warning">Warning</a>
                <a class="tooltip-button set-adminheader-style bg-purple" data-header-bg="bg-purple font-inverse" title="" href="javascript:void(0);" data-original-title="Purple">Purple</a>
                <a class="tooltip-button set-adminheader-style bg-black" data-header-bg="bg-black font-inverse" title="" href="javascript:void(0);" data-original-title="Black">Black</a>

                <div class="clear"></div>

                <h5 class="mrg15T">Gradients</h5>
                <a class="tooltip-button set-adminheader-style bg-gradient-1" data-header-bg="bg-gradient-1 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 1">Gradient 1</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-2" data-header-bg="bg-gradient-2 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 2">Gradient 2</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-3" data-header-bg="bg-gradient-3 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 3">Gradient 3</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-4" data-header-bg="bg-gradient-4 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 4">Gradient 4</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-5" data-header-bg="bg-gradient-5 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 5">Gradient 5</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-6" data-header-bg="bg-gradient-6 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 6">Gradient 6</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-7" data-header-bg="bg-gradient-7 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 7">Gradient 7</a>
                <a class="tooltip-button set-adminheader-style bg-gradient-8" data-header-bg="bg-gradient-8 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 8">Gradient 8</a>
            </div>
            <h5 class="header">
                Sidebar Style
                <a class="set-sidebar-style" data-header-bg="" title="Remove all styles" href="javascript:void(0);">Clear</a>
            </h5>
            <div class="theme-color-wrapper clearfix">
                <h5>Solids</h5>
                <a class="tooltip-button set-sidebar-style bg-primary" data-header-bg="bg-primary font-inverse" title="" href="javascript:void(0);" data-original-title="Primary">Primary</a>
                <a class="tooltip-button set-sidebar-style bg-green" data-header-bg="bg-green font-inverse" title="" href="javascript:void(0);" data-original-title="Green">Green</a>
                <a class="tooltip-button set-sidebar-style bg-red" data-header-bg="bg-red font-inverse" title="" href="javascript:void(0);" data-original-title="Red">Red</a>
                <a class="tooltip-button set-sidebar-style bg-blue" data-header-bg="bg-blue font-inverse" title="" href="javascript:void(0);" data-original-title="Blue">Blue</a>
                <a class="tooltip-button set-sidebar-style bg-warning" data-header-bg="bg-warning font-inverse" title="" href="javascript:void(0);" data-original-title="Warning">Warning</a>
                <a class="tooltip-button set-sidebar-style bg-purple" data-header-bg="bg-purple font-inverse" title="" href="javascript:void(0);" data-original-title="Purple">Purple</a>
                <a class="tooltip-button set-sidebar-style bg-black" data-header-bg="bg-black font-inverse" title="" href="javascript:void(0);" data-original-title="Black">Black</a>

                <div class="clear"></div>

                <h5 class="mrg15T">Gradients</h5>
                <a class="tooltip-button set-sidebar-style bg-gradient-1" data-header-bg="bg-gradient-1 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 1">Gradient 1</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-2" data-header-bg="bg-gradient-2 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 2">Gradient 2</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-3" data-header-bg="bg-gradient-3 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 3">Gradient 3</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-4" data-header-bg="bg-gradient-4 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 4">Gradient 4</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-5" data-header-bg="bg-gradient-5 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 5">Gradient 5</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-6" data-header-bg="bg-gradient-6 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 6">Gradient 6</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-7" data-header-bg="bg-gradient-7 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 7">Gradient 7</a>
                <a class="tooltip-button set-sidebar-style bg-gradient-8" data-header-bg="bg-gradient-8 font-inverse" title="" href="javascript:void(0);" data-original-title="Gradient 8">Gradient 8</a>
            </div>
        </div><div class="slimScrollBar" style="background: rgba(0, 0, 0, 0.3); width: 10px; position: absolute; top: 0px; opacity: 0.4; display: none; border-radius: 7px; z-index: 99; right: 1px; height: 524px;"></div><div class="slimScrollRail" style="width: 10px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(51, 51, 51); opacity: 0.2; z-index: 90; right: 1px;"></div></div>
    </div>
</div>
</div>

<div class="row">
    <div class="col-md-6">
       <!-- <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Bootstrap Modals
                </h3>
                <div class="example-box-wrapper">

                    <div class="modal fade display-block" tabindex="1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Modal content here ...</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		-->
		
       <!-- <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Triggers
                </h3>
                <div class="example-box-wrapper">

                    <button class="btn btn-default btn-md" data-toggle="modal" data-target="#myModal">
                        Launch demo modal
                    </button>

                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Modal content here ...sssssssaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				
            </div>
        </div>-->
		
		<button class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg"></button>

                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Large modal content here ...</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
        <!--<div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Size
                </h3>
                <div class="example-box-wrapper">

                   <button class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-lg"></button>

                    <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Large modal content here ...</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-default" data-toggle="modal" data-target=".bs-example-modal-sm"></button>

                   <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                    <h4 class="modal-title">Modal title</h4>
                                </div>
                                <div class="modal-body">
                                    <p>Small modal content here ...</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
					
					
                </div>
            </div>
        </div>
    </div>-->
	
	
	
	
    <!--<div class="col-md-6">
        <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    jQueryUI Dialog
                </h3>
                <div class="example-box-wrapper">
                    <a href="#" class="btn btn-primary basic-dialog">
                        Open dialog
                    </a>
                    <div class="hide" id="basic-dialog" title="Basic dialog title">
                        <div class="pad10A">
                            Dialog content here
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Overlays
                </h3>
                <div class="example-box-wrapper">
                    <a href="#" class="btn btn-default white-dialog">
                        White overlay
                    </a>
                    <a href="#" class="btn btn-default black-dialog">
                        Black overlay
                    </a>
                    <a href="#" class="btn btn-default green-dialog">
                        Green overlay
                    </a>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Dialogs Overlays
                </h3>
                <div class="example-box-wrapper">
                    <a href="#" class="btn btn-default opacity-dialog-30">
                        30% opacity
                    </a>
                    <a href="#" class="btn btn-default opacity-dialog-60">
                        60% opacity
                    </a>
                    <a href="#" class="btn btn-default opacity-dialog-80">
                        80% opacity
                    </a>
                </div>
            </div>
        </div>
		
		
        <div class="panel">
            <div class="panel-body">
                <h3 class="title-hero">
                    Generic Overlays
                </h3>
                <div class="example-box-wrapper">
                    <a href="#" class="btn btn-md btn-black overlay-button" data-style="light" data-theme="bg-black" data-opacity="60">
                        Black background overlay
                    </a>
                    <a href="#" class="btn btn-md btn-default overlay-button" data-style="dark" data-theme="bg-default" data-opacity="60">
                        Default background overlay
                    </a>
                    <a href="#" class="btn btn-md btn-default overlay-button" data-style="light" data-theme="bg-black" data-opacity="30">
                        Opacity 30%
                    </a>
                    <a href="#" class="btn btn-md btn-default overlay-button" data-style="light" data-theme="bg-black" data-opacity="60">
                        Opacity 60%
                    </a>
                    <a href="#" class="btn btn-md btn-default overlay-button" data-style="light" data-theme="bg-black" data-opacity="80">
                        Opacity 80%
                    </a>
                    <a href="#" class="btn btn-md btn-default overlay-button" data-style="light" data-theme="bg-black" data-opacity="100">
                        Opacity 100%
                    </a>
                </div>
            </div>
        </div>
		
		    </div>-->
	
	
</div>
                    </div>



            </div>
        </div>