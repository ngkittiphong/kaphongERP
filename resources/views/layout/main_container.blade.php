	<!--Page Container-->
	<section class="main-container">	
        <!--Page Header-->
        <div class="header">
            <div class="header-content">
                <div class="page-title"><i class="icon-display4"></i> {{ __t('dashboard.title', 'Dashboard') }}</div>
                <ul class="breadcrumb">
                    <li><a href="/">{{ __t('common.home', 'Home') }}</a></li>
                    <li><a href="index.htm#">{{ __t('menu.dashboards', 'Dashboards') }}</a></li>
                    <li class="active"><a href="index.htm#">{{ __t('dashboard.default_dashboard', 'Default Dashboard') }}</a></li>
                </ul>					
            </div>
        </div>		
        <!--/Page Header-->
        
        <div class="container-fluid page-content">
        
            
            
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="panel panel-flat no-border bg-slate-light">
                        <div class="panel-heading text-center heading-condensed">
                            <h6 class="panel-title text-uppercase text-muted">{{ __t('dashboard.earnings_graph', 'Earnings graph') }}</h6>						
                        </div>
                        <div class="p-10">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 m-t-10 m-b-10">
                                    <div class="map-container map-world-markers" style="max-height:240px;"></div>
                                </div>
                            </div>
                            <div class="row text-center p-t-20">
                                <div class="col-md-6 col-sm-12 hidden-sm">
                                    <h6 class="panel-title text-uppercase text-muted">{{ __t('dashboard.total_earnings_this_year', 'Total earnings this year') }}</h6>	
                                    <h1 class="panel-title text-uppercase text-size-huge text-semibold">$45,723</h1>	
                                    <div class="row m-t-20">
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <h6 class="panel-title text-uppercase text-muted">{{ __t('dashboard.america', 'America') }}</h6>	
                                            <h5 class="panel-title text-uppercase text-size-extralarge">$12,411</h5>	
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <h6 class="panel-title text-uppercase text-muted">{{ __t('dashboard.europe', 'Europe') }}</h6>	
                                            <h5 class="panel-title text-uppercase text-size-extralarge">$24,212</h5>	
                                        </div>
                                        <div class="col-md-4 col-sm-4 col-xs-4">
                                            <h6 class="panel-title text-uppercase text-muted">{{ __t('dashboard.asia', 'Asia') }}</h6>	
                                            <h5 class="panel-title text-uppercase text-size-extralarge">$9,100</h5>	
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <div class="chart" id="google-bar-stacked"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="panel panel-flat no-border bg-lime-darker no-margin">
                        <div class="panel-heading">
                            <h4 class="panel-title">{{ __t('dashboard.april_2016', 'April 2016') }}</h4>						
                        </div>
                        <div class="chart" id="google-line"></div>
                    </div>
                    <div class="panel panel-flat no-border no-margin p-20">	
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right p-t-10">
                                <h4 class="no-margin text-semibold text-lime-lighter">60%</h4>
                                <p class="text-muted text-uppercase">{{ __t('dashboard.direct_sell', 'Direct Sell') }}</p>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-4">
                                <div class="chart" id="google-donut-rotate"></div>
                            </div>	
                            <div class="col-md-4 col-sm-4 col-xs-4 text-left p-t-10">
                                <h4 class="no-margin text-semibold text-lime-lighter">40%</h4>
                                <p class="text-muted text-uppercase small">{{ __t('dashboard.channel_sell', 'Channel Sell') }}</p>
                            </div>	
                        </div>					
                    </div>
                    
                    <div class="panel panel-flat no-border m-t-20">	
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6 text-right p-t-20">
                                <h2 class="no-margin text-semibold text-purple-darker p-t-5">$34,356</h2>
                                <p class="text-muted text-uppercase">{{ __t('dashboard.expected_sales', 'Expected Sales') }}</p>
                            </div>						
                            <div class="col-md-6 col-sm-6 col-xs-6 text-left p-10 p-r-20">
                                <div class="chart" id="google-column-sales"></div>
                            </div>	
                        </div>					
                    </div>
                    
                    <div class="panel panel-flat no-border m-t-20 p-b-15">	
                        <div class="row">
                            <div class="col-md-4 col-sm-4 col-xs-4 text-right p-t-5">
                                <div class="wi wi-day-snow wi-40 m-l-10 text-amber p-t-20"></div>
                            </div>						
                            <div class="col-md-8 col-sm-8 col-xs-8 text-left">
                                <p class="text-muted text-uppercase no-margin no-padding p-t-15 p-b-10"><i class="icon-location3"></i> {{ __t('dashboard.new_york_rainy', 'New York (Rainy)') }}</p>
                                <div class="weather-cent text-amber-dark no-margin no-padding"><span>18</span></div>							
                            </div>	
                        </div>					
                    </div>							
                </div>			
            </div>
                    
            <div class="row">
                <div class="col-md-8 col-sm-8">
                    <div class="panel panel-flat no-border">																												
                        <img src="{{ asset('assets/images/covers/weather.jpg') }}" class="img-responsive img-100" alt="">
                        <div class="panel-body">													
                            <div class="row">
                                <div class="col-md-5 col-sm-5">
                                    <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.today', 'Today') }}</p>
                                    <div class="row-fluid no-margin">
                                        <div class="col-md-3 col-sm-3 col-xs-3 no-margin">
                                            <div class="wi wi-day-snow wi-30 p-l-20 p-t-15 text-teal"></div>
                                        </div>
                                        <div class="col-md-9 col-sm-9 col-xs-9 no-margin">
                                            <div class="weather-cent text-teal m-t-15"><span>22</span></div>
                                        </div>			
                                    </div>
                                </div>
                                <div class="col-md-7 col-sm-12 col-xs-12 p-r-20">
                                    <div class="row">
                                        <div class="col-md-2 col-xs-2 col-sm-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.mon', 'Mon') }}</p>
                                            <div class="wi wi-day-snow wi-20 p-l-20 text-indigo"></div>
                                            <div class="weather-cent wi-small text-muted"><span>17</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.tue', 'Tue') }}</p>
                                            <div class="wi wi-day-cloudy-windy wi-20 p-l-20 text-lime"></div>
                                            <div class="weather-cent wi-small text-muted"><span>19</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.wed', 'Wed') }}</p>
                                            <div class="wi wi-day-lightning wi-20 p-l-20 text-amber"></div>
                                            <div class="weather-cent wi-small text-muted"><span>18</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.thu', 'Thu') }}</p>
                                            <div class="wi wi-night-rain-mix wi-20 p-l-20 text-blue"></div>
                                            <div class="weather-cent wi-small text-muted"><span>21</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.fri', 'Fri') }}</p>
                                            <div class="wi wi-night-rain wi-20 p-l-20 text-slate"></div>
                                            <div class="weather-cent wi-small text-muted"><span>19</span></div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-2">
                                            <p class="text-muted text-uppercase no-margin">{{ __t('dashboard.sat', 'Sat') }}</p>
                                            <div class="wi wi-sunrise wi-20 p-l-20 text-success"></div>
                                            <div class="weather-cent wi-small text-muted"><span>18</span></div>
                                        </div>
                                    </div>
                                </div>			
                            </div>																
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4">
                    <div class="panel panel-flat no-border bg-teal no-margin">
                        <div class="clock" style="margin:0 auto;">
                          <div class="hours-container">
                            <div class="hours"></div>
                          </div>
                          <div class="minutes-container">
                            <div class="minutes"></div>
                          </div>
                          <div class="seconds-container">
                            <div class="seconds"></div>
                          </div>
                        </div>
                    </div>
                    <div class="panel panel-flat no-border no-margin m-b-20 p-t-10 p-b-10">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <div class="p-l-20 p-t-5 p-b-5 text-teal">
                                    <h3 class="no-padding no-margin">April 24</h3>
                                    <p class="text-left no-padding no-margin">2016, Sunday</p>
                                    <p class="text-left no-padding no-margin">6:18 PM</p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6 text-right text-teal-lighter p-r-20 p-t-15 p-b-5">
                                <i class="icon-alarm icon-4x m-r-10"></i>
                            </div>
                        </div>
                    </div>
                </div>			
            </div>
            
            <div class="row">
                <div class="col-md-3 col-sm-4">
                    <div class="panel panel-flat no-border">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="icon-feed position-left"></i>{{ __t('dashboard.recent_activities', 'Recent Activities') }}</h4>									
                        </div>
                        <div class="panel-body">
                            <div class="m-b-10">
                                <div class="text-muted"><i class="icon-comment position-left"></i> 5 minutes ago</div>
                                <a href="index.htm">Tyler Rivera</a> updated his status
                            </div>
                            <div class="m-b-10">
                                <div class="text-muted"><i class="icon-image2 position-left"></i> 14 minutes ago</div>
                                <a href="index.htm">Edward Harvey</a> uploaded a photo
                            </div>
                            <div class="m-b-10">
                                <div class="text-muted"><i class="icon-video-camera position-left"></i> 4 hours ago</div>
                                <a href="index.htm">Aaron Gibson</a> added a video
                            </div>
                            <div class="m-b-10">
                                <div class="text-muted"><i class="icon-video-camera position-left"></i> 1 day ago</div>
                                <a href="index.htm">Tyler Rivera</a> added a video
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9 col-sm-8">
                    <div class="panel panel-flat no-border">
                        <div class="panel-heading p-t-15 p-l-20">
                            <h4 class="panel-title"><i class="icon-users position-left"></i>{{ __t('dashboard.latest_users', 'Latest users') }}</h4>				
                        </div>
                        <div class="table-responsive">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>{{ __t('dashboard.first_name', 'First Name') }}</th>
                                        <th>{{ __t('dashboard.last_name', 'Last Name') }}</th>
                                        <th>{{ __t('dashboard.username', 'Username') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Jane</td>
                                        <td>Elliott</td>
                                        <td>#jane</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Florence</td>
                                        <td>Douglas</td>
                                        <td>#florence</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Eugine</td>
                                        <td>Turner</td>
                                        <td>#eugine</td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>Ann</td>
                                        <td>Porter</td>
                                        <td>#ann</td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td>Andrew</td>
                                        <td>Brewer</td>
                                        <td>#andrew</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>			
            </div>      
    </div>

    <!--Footer -->
    <footer class="footer-container">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="footer-left">
                        <span>© 2016 Penguin&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;Web app kit by <a href="http://followtechnique.com" target="_blank">FollowTechnique</a>&nbsp;&nbsp;&nbsp;&bull;&nbsp;&nbsp;&nbsp;Version - 1.1.0
                    </span>
                    </div>
                </div>				
            </div>
        </div>
    </footer>
    <!--/Footer-->
    
</section>
<!--/Page Container-->