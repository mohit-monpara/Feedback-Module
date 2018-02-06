<?php
echo $this->Html->css('navigation');
?>
<br><div class="navbar navbar-default navbar-static-top" style="background-color:#fff">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
             <p style="padding-left: 20px;">   <?php echo $this->Html->image('gnulogo.png', array('alt' => 'GNU', 'border' => '0')); ?></p>
             </div>
        <div class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
      <li><?php echo $this->Html->link(__("Home"),array('plugin'=>false,'controller' => 'users', 'action' => 'dashboard')) ?></li>
      <li><?php echo $this->Html->link(__("Dashboard"),array('plugin'=>'feedback_system','controller' => 'pages', 'action' => 'dashboard')) ?></li>
      
      <li class="dropdown menu-large">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Feedback System<b class="caret"></b></a>       
        <ul class="dropdown-menu megamenu row">
          

          <li class="col-sm-3">
            <ul>
              
              <?php if(Auth::hasRoles(['developer','superadmin'])) {?>
              <li class="dropdown-header">Feedback Categories & Events</li>
              <li><?php echo $this->Html->link(__("New General Category",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'add')) ?></li>
              <li><?php echo $this->Html->link(__("View General Categories",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'index')) ?></li>
              <li><?php echo $this->Html->link(__("New Event(Institution-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_ins'))?></li>
              <li><?php echo $this->Html->link(__("New Event(Department-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_dept'))?></li>
              <li><?php echo $this->Html->link(__("View Events",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'index'))  ?></li>
              <li class="divider"></li> 
              <?php } ?>


              
              <?php if(Auth::hasRoles(['fbadmin'])) {?>
              <li class="dropdown-header">Feedback Categories & Events</li>
              <li><?php echo $this->Html->link(__("New General Category",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'category_add')) ?></li>
              <li><?php echo $this->Html->link(__("View General Categories",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'index_institution'))  ?></li>
              <li><?php echo $this->Html->link(__("New Event(Institution-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_ins_adm')) ?></li>
              <li><?php echo $this->Html->link(__("New Event(Department-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_dept_adm')) ?></li>
              <li><?php echo $this->Html->link(__("View Events",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'index_institution'))  ?></li>
              <li class="divider"></li> 
              <?php } ?>  


           <!--    <?php if(Auth::hasRoles(['fbcoordinator'])) {?>
              <li class="dropdown-header">Feedback Categories</li>
              <li><?php echo $this->Html->link(__("New General Category",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'category_add')) ?></li>
              <li><?php echo $this->Html->link(__("View General Categories",true),array('plugin'=>'feedback_system','controller' => 'feedback_categories', 'action' => 'index_institution'))  ?></li>
              <?php } ?>  -->
<!-- 
               <?php if(Auth::hasRoles(['fbeventcoordinator'])) {?>
              <li class="dropdown-header">Feedback Events</li>
              <li><?php echo $this->Html->link(__("New Event(Institution-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_ins_adm')) ?></li>
              <li><?php echo $this->Html->link(__("New Event(Department-Wise) ",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'add_dept_adm')) ?></li>
              <li><?php echo $this->Html->link(__("View Events",true),array('plugin'=>'feedback_system','controller' => 'feedback_events', 'action' => 'index_institution'))  ?></li>
              <?php } ?>  -->
              
              

              <?php if(Auth::hasRoles(['developer','superadmin'])) {?>
              <li class="dropdown-header">Manage Coordinators</li>
              <li><?php echo $this->Html->link(__("Manage Coordinator",true),array('plugin'=>'feedback_system','controller' => 'feedback_manages', 'action' => 'add')) ?></li>
              <li><?php echo $this->Html->link(__("View Managed Coordinators",true),array('plugin'=>'feedback_system','controller' => 'feedback_manages', 'action' => 'index'))  ?></li>
              <li><?php echo $this->Html->link(__("Event Manage Coordinator",true),array('plugin'=>'feedback_system','controller' => 'feedback_event_manages', 'action' => 'add')) ?></li>
              <li><?php echo $this->Html->link(__("View Event Managed Coordinators",true),array('plugin'=>'feedback_system','controller' => 'feedback_event_manages', 'action' => 'index'))  ?></li>
              <?php } ?>


              <?php if(Auth::hasRoles(['fbcoordinator'])) {?>
              <li class="dropdown-header">Manages questions</li>
              <li><?php echo $this->Html->link(__("New questions for General Feedback"),array('plugin'=>'feedback_system','controller' => 'feedback_questions', 'action' => 'add_cord_question')) ?></li>
              <li><?php echo $this->Html->link(__("View all General questions "),array('plugin'=>'feedback_system','controller' => 'feedback_questions', 'action' => 'index_adm')) ?></li>
              <?php } ?>

              <?php if(Auth::hasRoles(['fbeventcoordinator'])) {?>
              <li class="dropdown-header">Manages questions</li>
              <li><?php echo $this->Html->link(__("New questions for Event Feedback"),array('plugin'=>'feedback_system','controller' => 'feedback_event_questions', 'action' => 'add_cord_question')) ?></li>
              <li><?php echo $this->Html->link(__("View all Event questions "),array('plugin'=>'feedback_system','controller' => 'feedback_event_questions', 'action' => 'index_adm')) ?></li>
              <?php } ?>


              <?php if(Auth::hasRoles(['fbadmin'])) {?>
              <li class="dropdown-header">Manage Coordinators</li>
              <li><?php echo $this->Html->link(__("Manage Coordinator",true),array('plugin'=>'feedback_system','controller' => 'feedback_manages', 'action' => 'add_adm_coordinator')) ?></li>
              <li><?php echo $this->Html->link(__("View Managed Coordinators",true),array('plugin'=>'feedback_system','controller' => 'feedback_manages', 'action' => 'index_admin'))  ?></li>
              <li><?php echo $this->Html->link(__("Event Manage Coordinator",true),array('plugin'=>'feedback_system','controller' => 'feedback_event_manages', 'action' => 'add_adm_coordinator')) ?></li>
              <li><?php echo $this->Html->link(__("View Event Managed Coordinators",true),array('plugin'=>'feedback_system','controller' => 'feedback_event_manages', 'action' => 'index_admin'))  ?></li>
              <?php } ?> 

            </ul>
          </li>


          <li class="col-sm-3">
          <ul>

               <?php if(Auth::hasRoles(['fbadmin'])) {?>
              <li class="dropdown-header">Manages questions</li>
              <li><?php echo $this->Html->link(__("New questions for General Feedback"),array('plugin'=>'feedback_system','controller' => 'feedback_questions', 'action' => 'add_adm_question')) ?></li>
              <li><?php echo $this->Html->link(__("View all General questions "),array('plugin'=>'feedback_system','controller' => 'feedback_questions', 'action' => 'index')) ?></li>
               <li><?php echo $this->Html->link(__("New questions for Event Feedback"),array('plugin'=>'feedback_system','controller' => 'feedback_event_questions', 'action' => 'add_adm_question')) ?></li>
              <li><?php echo $this->Html->link(__("View all Event questions "),array('plugin'=>'feedback_system','controller' => 'feedback_event_questions', 'action' => 'index')) ?></li>
               <li class="divider"></li> 
               <?php } ?>


              <li class="dropdown-header">Feedback Form</li>
              <li><?php echo $this->Html->link(__("Student General Feedback Form",true),array('plugin'=>'feedback_system','controller' => 'feedback_questions', 'action' => 'category_show')) ?></li>
              <li><?php echo $this->Html->link(__("Student Event Feedback Form",true),array('plugin'=>'feedback_system','controller' => 'feedback_event_questions', 'action' => 'event_show')) ?>
              </li>
              </ul>       
              </li>     
         
          <li class="col-sm-3">
            <ul>
              
            
              <?php if(Auth::hasRoles(['developer','superadmin'])) {?>
              <li class="dropdown-header">General Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback_pie')) ?></li>
               <li class="divider"></li> 
               <?php } ?>

               <?php if(Auth::hasRoles(['fbadmin'])) {?>
              <li class="dropdown-header">General Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback_admin')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback_pie_admin')) ?></li>
               <li class="divider"></li> 
               <?php } ?>

                <?php if(Auth::hasRoles(['fbcoordinator'])) {?>
              <li class="dropdown-header">General Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback_coordinator')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'category_feedback_pie_coordinator')) ?></li>
               <li class="divider"></li> 
               <?php } ?>

             <?php if(Auth::hasRoles(['developer','superadmin'])) {?>
              <li class="dropdown-header">Event Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback_pie')) ?></li>
              <li class="divider"></li> 
              <?php } ?>

              <?php if(Auth::hasRoles(['fbadmin'])) {?>
              <li class="dropdown-header">Event Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback_admin')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback_pie_admin')) ?></li>
              <li class="divider"></li> 
               <?php } ?>

               <?php if(Auth::hasRoles(['fbeventcoordinator'])) {?>
              <li class="dropdown-header">Event Report</li>
              <li><?php echo $this->Html->link(__("Column Chart"),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback_coordinator')) ?></li>
              <li><?php echo $this->Html->link(__("Pie Chart "),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'event_feedback_pie_coordinator')) ?></li>
              <li class="divider"></li> 
               <?php } ?>


            <?php if(Auth::hasRoles(['fbadmin','developer','superadmin'])) {?>
              <li class="dropdown-header">Export Report</li>
              <li><?php echo $this->Html->link(__("General Feedback Report"),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'export_all')) ?></li>
              <li><?php echo $this->Html->link(__("Event Feedback Report"),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'export_all')) ?></li>
              <li class="divider"></li>
               <?php } ?>
            
             <?php if(Auth::hasRoles(['fbcoordinator'])) {?>
              <li class="dropdown-header">Export Report</li>
              <li><?php echo $this->Html->link(__("General Feedback Report"),array('plugin'=>'feedback_system','controller' => 'feedback_answers', 'action' => 'export_all')) ?></li>
              
               <?php } ?>

               <?php if(Auth::hasRoles(['fbeventcoordinator'])) {?>
              <li class="dropdown-header">Export Report</li>
              <li><?php echo $this->Html->link(__("Event Feedback Report"),array('plugin'=>'feedback_system','controller' => 'feedback_event_answers', 'action' => 'export_all')) ?></li>
              
               <?php } ?>
                 
            </ul>
          </li>  
             
        </ul>
      </li>

       <li><?php echo $this->Html->link(__("Logout",true),array('controller' => 'users' ,'action'=>'logout' ,'plugin'=>false)) ?></li>
    </ul>
    </div>
      </div>
</div>