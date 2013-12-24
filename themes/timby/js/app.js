// app.js

var App = function(){
    var Timby = {};
    window.Timby = Timby;
    var reports_layer = null;
    var base_map = null;
    var num_layers = 0;

    Timby.current_sector = 0;
    Timby.current_timeline_value = 1;

    var template = function(name) {
        return Mustache.compile($('#'+name+'-template').html());
    };

    Timby.Index = Backbone.View.extend({
        initialize : function(){
            this.reportLayerUrl = 'http://timby.cartodb.com/api/v2/viz/db2f6d30-443d-11e3-bbef-5fc57e5d5ce4/viz.json';
            this.setupBaseMap();
            this.setupCartoDBLayer();
            this.render();
        },

        render : function(){
            this.$el.html('');

            // create the gear button for changing the settings
            // the actual settings modal is created inside this method too
            var gear = new Timby.GearButton(this.map, this.baselayer, 'topright');

            // Create the legend that states the marker colors
            var legend = new Timby.LegendView(
                this.map,
                'bottomleft',
                sectors
            ); // color can take rgb, rgba, hex, or text
            return this;
        },

        setupBaseMap : function () {
            this.map = L.map('map', { attributionControl: false}).setView([6.779171028142874, -8.9373779296875], 8);

            // this.attr = new L.Control.Attribution({position: 'topleft'}).addTo(this.map);
            // this.zoom = new L.Control.Zoom({ position: 'bottomleft' }).addTo(this.map);

            this.baselayer = L.tileLayer('http://{s}.maptile.lbs.ovi.com/maptiler/v2/maptile/newest/normal.day/{z}/{x}/{y}/256/png8?token=x4FPrgPvCoVxpWbvDVjD-g&app_id=7INhahrI8e6fBdCx9Qgd', {
                subdomains: '1234'
            });
            this.baselayer.addTo(this.map);
            base_map = this.map;
        },

        setupCartoDBLayer : function () {

            var self = this;

            cartodb.createLayer(this.map, this.reportLayerUrl)
                .addTo(this.map)
                .done(function(reports){
                    reports.setInteraction(true);

                    // tell the layer what columns we want from click events
                    reports.getSubLayer(0).set({interactivity: 'url, title'})

                    // register a click event
                    reports.on('featureClick', function(e, pos, latlng, data) {

                        // Toggle controls

                        $('.gear').hide();
                        $('.sidepanel').show('slow');

                        var url = data.url;

                        if (window.location.protocol != "https:")
                        {
                            url = url.replace("https:", "http:");
                        }
                        else
                        {
                            url = url.replace("http:", "https:");
                        }

                        $.get(url, function(data) {
                            $('.sidepanel').html(data);

                            $('.sidepanel .close').click(function(){
                                $('.sidepanel').hide('slow');
                                $('.gear').show();
                                self.reports.setInteraction(false);
                                self.reports.hide();

                                reports.setInteraction(true);
                                reports.show();
                            });
                        });

                        reports.setInteraction(false);
                        reports.hide();
                    });

                    self.reports = reports;
                    reports_layer = reports;
                    num_layers = 1;
                    Timby.current_sector = 0;
                }
            );
        }
    });

    Timby.filterBySector = function(sector_id) {
        this.reportLayerUrl = 'http://timby.cartodb.com/api/v2/viz/17cd0c28-6bcd-11e3-b468-455646add620/viz.json';
        filterFromDate = new Date(min_timeline_year, min_timeline_month, min_timeline_day);
        currentFilterFromDate = new Date();
        currentFilterToDate = new Date();

        currentFilterFromDate.setDate(filterFromDate.getDate() + (timeline_step_interval * Timby.current_timeline_value));
        currentFilterToDate.setDate(currentFilterFromDate.getDate() + timeline_step_interval);

        dateFromString = currentFilterFromDate.getFullYear() + "-" + (currentFilterFromDate.getMonth() + 1) + "-" +
            currentFilterFromDate.getDate() + " 00:00:00";
        dateToString = currentFilterToDate.getFullYear() + "-" + (currentFilterToDate.getMonth() + 1) + "-" +
            currentFilterToDate.getDate() + " 23:59:59";

        if(reports_layer != null)
        {
            reports_layer.getSubLayer(num_layers - 1).remove();
        }

        cartodb.createLayer(base_map, this.reportLayerUrl)
            .addTo(base_map)
            .done(function(reports){

                sql_statement = "SELECT * FROM dashboard";

                if(sector_id > 0)
                {
                    sql_statement += " WHERE sector = " + sector_id;
                    sql_statement += " AND item_date BETWEEN '" + dateFromString + "' AND '" + dateToString + "'";
                }
                else
                {
                    sql_statement += " WHERE item_date BETWEEN '" + dateFromString + "' AND '" + dateToString + "'";
                }

                reports.createSubLayer(
                    {
                        sql: sql_statement,
                        cartocss: "#dashboard{" +
                            "marker-width: 12;" +
                            "marker-fill: #FF6600;" +
                            "marker-opacity: 0.9;" +
                            "marker-allow-overlap: true;" +
                            "marker-placement: point;" +
                            "marker-type: ellipse;" +
                            "marker-line-width: 2;" +
                            "marker-line-color: #FFF;" +
                            "marker-line-opacity: 1;" +
                        "}"
                    }
                );

                reports.getSubLayer(1).setInteraction(true);
                reports.getSubLayer(1).set({interactivity: 'url, title'})
                reports.setInteraction(true);

                // register a click event
                reports.on('featureClick', function(e, pos, latlng, data) {

                    // Toggle controls

                    $('.gear').hide();
                    $('.sidepanel').show('slow');

                    var url = data.url;

                    if (window.location.protocol != "https:")
                    {
                        url = url.replace("https:", "http:");
                    }
                    else
                    {
                        url = url.replace("http:", "https:");
                    }

                    $.get(url, function(data) {
                        $('.sidepanel').html(data);

                        $('.sidepanel .close').click(function(){
                            $('.sidepanel').hide('slow');
                            $('.gear').show();
                            self.reports.setInteraction(false);
                            self.reports.hide();

                            reports.setInteraction(true);
                            reports.show();
                        });
                    });

                    reports.setInteraction(false);
                    reports.hide();
                });

                self.reports = reports;
                reports_layer = reports;

                num_layers = 2;
                Timby.current_sector = sector_id;
            }
        );
    }

    Timby.LegendView = Backbone.View.extend({
        template : template('legend'),
        events : {
        },

        initialize : function(map, position, collection) {
            this.map = map;
            this.position = position;
            this.collection = collection;
            var template = this.template;
            this.controller = L.Control.extend({
                options: {
                    position: position
                },
                onAdd: function (map) {
                    var container = L.DomUtil.create('div', 'map-legend');
                    return container;
                }
            });
            this.map.addControl(new this.controller());
            this.render();
        },

        render : function () {
            this.$el = $('.map-legend');
            this.$el.html(this.template(this.collection));
            return this;
        }
    });


    Timby.GearButton = Backbone.View.extend({
        template : template('gear'),
        events : {
            'click' :'toggleModal'
        },

        initialize : function(map, baselayer, position) {
            this.map = map;
            this.baselayer = baselayer;
            var template = this.template;
            this.controller = L.Control.extend({
                options: {
                    position: position
                },
                onAdd: function (map) {
                    var container = L.DomUtil.create('div', 'gear-button');
                    return container;
                }
            });
            this.map.addControl(new this.controller());
            this.render();
        },

        render : function () {
            this.$el = $('.gear-button');
            this.$el.html(this.template);
            this.settings = new Timby.SettingsView(this.baselayer);
            return this;
        },

        toggleModal : function () {
            if ($('.settings').hasClass('show')){
                $('.settings').removeClass('show')
            } else {
                $('.settings').addClass('show')
            }
        }
    });

    Timby.SettingsView = Backbone.View.extend({
        template : template('settings'),
        events : {
            'click .basemap': 'setBasemap'
        },

        initialize : function(baselayer) {
            this.baselayer = baselayer;
            this.render();
        },

        render : function () {
            this.$el.html(this.template);
            $('body').append(this.$el);

            $('.settings .close').click(function(){$('.settings').removeClass('show')});

            return this;
        },

        setBasemap : function (e) {
            var bm = $(e.target).parent().attr('id');
            if (bm=='toner') {
                this.baselayer.options.subdomains = ['a','b','c']
                this.baselayer.setUrl('http://{s}.tile.stamen.com/toner/{z}/{x}/{y}.png')
            } else if (bm=='terrain') {
                this.baselayer.options.subdomains = ['1','2','3','4']
                this.baselayer.setUrl('http://{s}.maptile.lbs.ovi.com/maptiler/v2/maptile/newest/terrain.day/{z}/{x}/{y}/256/png8?token=x4FPrgPvCoVxpWbvDVjD-g&app_id=7INhahrI8e6fBdCx9Qgd')
            } else if (bm=='normal') {
                this.baselayer.options.subdomains = ['1','2','3','4']
                this.baselayer.setUrl('http://{s}.maptile.lbs.ovi.com/maptiler/v2/maptile/newest/normal.day/{z}/{x}/{y}/256/png8?token=x4FPrgPvCoVxpWbvDVjD-g&app_id=7INhahrI8e6fBdCx9Qgd')
            } else if (bm=='forest') {
                // console.log(this.baselayer)
                this.baselayer.options.subdomains = ['a','b','c']
                this.baselayer.setUrl('http://{s}.tile.thunderforest.com/outdoors/{z}/{x}/{y}.png')
            }
        }
    });

    Timby.Router = Backbone.Router.extend({
        initialize : function(options){
            this.el = options.el;
        },
        routes : {
            "" : "index",
        },
        index : function(){
            var indexView = new Timby.Index();
            this.el.empty();
        }
    });

    var router = new Timby.Router({el : $('#main')});
    Backbone.history.start();
};
var b;