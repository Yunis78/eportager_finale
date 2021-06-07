/************************fonction creation de card *****************************************/
/*****************************************************************/

(function() {

    L.Control.ListMarkers = L.Control.extend({
    
        includes: L.version[0]==='1' ? L.Evented.prototype : L.Mixin.Events,
    
        options: {		
            layer: false,
            maxItems: 20,
            collapsed: false,		
            label: 'title',
            itemIcon: L.Icon.Default.Path,
            itemArrow: '&#10148;',	//visit: http://character-code.com/arrows-html-codes.php
            maxZoom: 9,
            //TODO autocollapse
        },
    
        // inlisialise les layer
        initialize: function(options) {
            L.Util.setOptions(this, options);
            this._container = null;
            this._list = null;
            this._layer = this.options.layer || new L.LayerGroup();
        },
    
        _onAdd: function (map) {

            this._map = map;

            var container =  this._container = document.getElementById('cardproducer');

            var divResponse = document.createElement('div')
                divResponse.classList = 'col-12 col-md-12';
            this._list = container.appendChild(divResponse);

            // this._initToggle();

            map.on('moveend', this._updateList, this);

            this._updateList();

            return container;
        },
        get onAdd() {
            return this._onAdd;
        },
        set onAdd(value) {
            this._onAdd = value;
        },
        
        // retire les cards des information sur la carte en bas à droit 

        onRemove: function(map) {
            map.off('moveend', this._updateList, this);
            this._container = null;
            this._list = null;		
        },

        _createItem: function(layer ) {
            
                var card = L.DomUtil.create('div', 'card mb-3'),
                    producteurInfo = L.DomUtil.create('div', 'producteur w-100 d-flex flex-row align-items-center', card),
                    producteurProfil = L.DomUtil.create('div', 'm-3 cardmap', producteurInfo),
                    producteurImg = L.DomUtil.create('img', 'img-profile', producteurProfil),
                    producteurTitre = L.DomUtil.create('h5', 'card-title titre font-weight-bold', producteurInfo),
                    producteurAddress = L.DomUtil.create('p', 'card-text text-producer ml-5', producteurInfo),
                    producteurbtn = L.DomUtil.create('div', 'btn-discover-producer col-12 d-flex flex-row align-items-center', producteurInfo),
                    that = this;

                    // console.log('_createItem',layer.options.title);
                L.DomEvent
                    .disableClickPropagation(card)
                    .on(card, 'click', L.DomEvent.stop, this)
                    .on(card, 'click', function(e) {
                        this._moveTo( layer.getLatLng() );
                    }, this)
                    .on(card, 'mouseover', function(e) {
                        that.fire('item-mouseover', {layer: layer });
                    }, this)
                    .on(card, 'mouseout', function(e) {
                        that.fire('item-mouseout', {layer: layer });
                    }, this);
                    // console.log('name',layer.options.title);

                    var layerLatLng = layer.getLatLng();
                    // console.log('lol',lol.lat, lol.lng);
                    for (let i = 0; i < Object.keys(layer.options.title).length; i++) {
                        const element = layer.options.title[i];
                        if (layerLatLng.lat === element.latitude && layerLatLng.lng === element.longitude) {
                        // console.log('address',layer.options.title[i].name , layer.options.title[i].address);
                        producteurImg.src = '/img/LOGO5.svg';
                        producteurAddress.textContent = layer.options.title[i].address;
                        producteurTitre.textContent = layer.options.title[i].name;
                        // console.log('elment', element.latitude, element.longitude);
                        }
                    }
                        return card;
	    },

        _updateList: function() {
	
            var that = this,
                n = 0;
            
            this._list.innerHTML = '';
            // console.log(that._list);

            this._layer.eachLayer(function(layer) {
                if(layer instanceof L.Marker){
                    if( that._map.getBounds().contains(layer.getLatLng()) ){
                        // console.log('that._map.getBounds().contains(layer.getLatLng())',that._map.getBounds().contains(layer.getLatLng()))
                        if(n < that.options.maxItems){
                            // console.log('n',n);
                        // console.log('layer',layer)
                        // console.log('that',that);
                        // console.log('that',that._createItem(layer));
                        // console.log('that',layer.options);
                        // console.log('layer',layer);
                        that._list.appendChild( that._createItem(layer,n));
                        n++
                        }  
                    }
                    
                }
            });
        },
    
        // _initToggle: function () {
    
        //     /* inspired by L.Control.Layers */
    
        //     var container = this._container;
    
        //     //Makes this work on IE10 Touch devices by stopping it from firing a mouseout event when the touch is released
        //     container.setAttribute('aria-haspopup', true);
    
        //     if (!L.Browser.touch) {
        //         L.DomEvent
        //             .disableClickPropagation(container);
        //             //.disableScrollPropagation(container);
        //     } else {
        //         L.DomEvent.on(container, 'click', L.DomEvent.stopPropagation);
        //     }
    
        //     if (this.options.collapsed)
        //     {
        //         this._collapse();
    
        //         if (!L.Browser.android) {
        //             L.DomEvent
        //                 .on(container, 'mouseover', this._expand, this)
        //                 .on(container, 'mouseout', this._collapse, this);
        //         }
        //         var link = this._button = L.DomUtil.create('a', 'list-markers-toggle', container);
        //         link.href = '#';
        //         link.title = 'List Markers';
    
        //         if (L.Browser.touch) {
        //             L.DomEvent
        //                 .on(link, 'click', L.DomEvent.stop)
        //                 .on(link, 'click', this._expand, this);
        //         }
        //         else {
        //             L.DomEvent.on(link, 'focus', this._expand, this);
        //         }
    
        //         this._map.on('click', this._collapse, this);
        //         // TODO keyboard accessibility
        //     }
        // },
    
        // _expand: function () {
        //     this._container.className = this._container.className.replace(' list-markers-collapsed', '');
        // },
    
        // _collapse: function () {
        //     L.DomUtil.addClass(this._container, 'list-markers-collapsed');
        // },
    
        // _moveTo: function(latlng) {
        //     if(this.options.maxZoom)
        //         this._map.setView(latlng, Math.min(this._map.getZoom(), this.options.maxZoom) );
        //     else
        //         this._map.panTo(latlng);    
        // }
    // });
    
    // L.control.listMarkers = function (options) {
    //     return new L.Control.ListMarkers(options);
    // };
    
    // L.Map.addInitHook(function () {
    //     if (this.options.listMarkersControl) {
    //         this.listMarkersControl = L.control.listMarkers(this.options.listMarkersControl);
    //         this.addControl(this.listMarkersControl);
    //     }
    });
    
    }).call(this);

    
//*******************************************************************************/