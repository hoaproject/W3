if(undefined === Hoa)
    var Hoa = {};

Hoa.Graph = (Hoa.Graph || function ( __document__, __width__, __height__ ) {

    var paper   = this;
    var Element = function ( name ) {

        var that      = this;
        var translate = null;

        this.type = name;

        this.parent = __document__;

        this.node = document.createElementNS(
            'http://www.w3.org/2000/svg',
            name
        );

        this.append = function ( element ) {

            that.node.appendChild(element.node);
            element.parent = that;

            return that;
        };

        this.remove = function ( element ) {

            that.node.removeChild(element.node);
            element.parent = null;

            return that;
        };

        this.attr = function ( object ) {

            if('string' === typeof object)
                return that.node.getAttribute(object);

            for(key in object)
                that.node.setAttribute(key, object[key]);

            return that;
        };

        this.getBBox = function ( ) {

            var bbox = that.node.getBBox();

            if(null === translate)
                return bbox;

            var out = {
                height: bbox.height,
                width : bbox.width,
                x     : bbox.x + translate.x,
                y     : bbox.y + translate.y
            };

            return out;
        };

        this.move = function ( x, y ) {

            var bbox = that.getBBox();
            var sign = null;

            if('string' === typeof x) {

                sign = x.charAt(0);

                if('+' === sign || '-' === sign)
                    x = bbox.x + parseInt(x);
                else
                    x = parseInt(x);
            }

            if('string' === typeof y) {

                sign = y.charAt(0);

                if('+' === sign || '-' === sign)
                    y = bbox.y + parseInt(y);
                else
                    y = parseInt(y);
            }

            if('g' === that.type) {

                that.attr({transform: 'translate(' + x + ', ' + y +')'});
                translate = {x: x, y: y};
            }
            else
                that.attr({x: x, y: y});
        }

        return this;
    };

    this.element = function ( name ) {

        var element = new Element(name);
        svg.append(element);

        return element;
    };

    this.rect = function ( x, y, width, height, radius, text ) {

        var group = new Element('g');
        svg.append(group);

        var rect = new Element('rect');
        rect.attr({
            x             : x,
            y             : y,
            width         : width,
            height        : height,
            rx            : radius || 3,
            ry            : radius || 3,
            fill          : 'url(#boxFill)',
            stroke        : '#fff',
            'stroke-width': '2px',
            filter        : 'url(#shadow)'
        });
        group.append(rect);

        if(undefined !== text) {

            var textBox = paper.text(x + width / 2, y + height / 2, text)
                               .attr({fill: '#ffffbe'});
            group.append(textBox);
        }

        return group;
    };

    this.text = function ( x, y, text ) {

        var text_ = new Element('text');
        text_.attr({
            x                  : x,
            y                  : y,
            fill               : '#000',
            'text-anchor'      : 'middle',
            'dominant-baseline': 'middle'
        });
        text_.node.textContent = text;
        svg.append(text_);

        return text_;
    };

    this.path = function ( ) {

        var path = new Element('path');
        path.attr({
            fill          : 'none',
            stroke        : '#000',
            'stroke-width': '1px'
        });
        svg.append(path);

        return path;
    };

    this.grid = function ( x, y, width, height, columns, lines ) {

        var matrix      = [];
        var columnWidth = width / columns;
        var lineHeight  = height / lines;
        var stepX       = columnWidth / 2;
        var accX        = x;
        var accY        = y + lineHeight / 2;

        for(var l = 0; l < lines; ++l) {

            accX       = x;
            matrix[l]  = [];

            for(var c = 0; c < columns; ++c) {

                matrix[l][c] = {x: accX += stepX, y: accY};
                accX += stepX;
            }

            accY += lineHeight;
        }

        return {

            push: new function ( ) {

                var lastPosition = - 1;

                return function ( element, ix, iy ) {

                    var position = null;

                    if(undefined === iy)
                        position = ix || null;
                    else if(undefined !== ix)
                        position = ix * columns + iy;

                    if(null === position)
                        position = lastPosition + 1;

                    var point = matrix[Math.floor(position / columns)]
                                      [position % columns];
                    var bbox  = element.getBBox();
                    element.move(
                        point.x - bbox.width / 2,
                        point.y - bbox.height / 2
                    );
                    lastPosition = position;

                    return element;
                };
            }
        };
    };

    this.link = new function ( ) {

        var compute = function ( from, to ) {

            var fromBbox = from.getBBox();
            var toBbox   = to.getBBox();

            var fx  = fromBbox.x;
            var fy  = fromBbox.y;
            var fw  = fromBbox.width;
            var fh  = fromBbox.height;
            var tx  = toBbox.x;
            var fw2 = fw / 2;
            var fh2 = fh / 2;
            var ax  = fx + fw2;
            var by  = toBbox.y + toBbox.height / 2;
            var cx  = tx       + toBbox.width  / 2;
            var cy  = fy + fh2;
            var d   = fw2 / (cx - ax) * (cy - by);

            var points = {
                fromX: fx + fw,
                fromY: (fy + fh / 2) - d,
                toX  : tx,
                toY  : by + d
            };
            points.fromCX = points.toX - (points.toX - points.fromX) / 2;
            points.fromCY = points.fromY;
            points.toCX   = points.fromCX;
            points.toCY   = points.toY;

            return points;
        };

        this.between = function ( from, to, text ) {

            var link    = paper.path()
                               .attr({'marker-end': 'url(#arrow)'})
            var textBox = null;

            if(undefined !== text)
                textBox = paper.text(0, 0, text)
                               .attr({fill: '#f18d05'})

            var out     = {};
            out.element = link;
            out.text    = textBox;
            out.repaint = (function ( from, to, line ) {

                return function ( ) {

                    var points = compute(from, to);
                    line.attr({
                        d: 'M ' + points.fromX     + ',' + points.fromY  +
                           ' C' + points.fromCX    + ',' + points.fromCY +
                           ' '  + points.toCX      + ',' + points.toCY   +
                           ' '  + (points.toX - 8) + ',' + points.toY
                    });

                    if(null !== textBox)
                        textBox.move(
                            (points.fromX + points.toX) / 2,
                            (points.fromY + points.toY) / 2 - 20
                        );

                    return this;
                };
            })(from, to, link);

            out.repaint();

            return out;
        };
    };

    var svg = new Element('svg');
    svg.attr({
        version: '1.1',
        width  :  __width__,
        height : __height__
    });
    __document__.appendChild(svg.node);

    if(0 !== __width__ && 0 !== __height__) {

        __document__.style.width  = __width__ + 'px';
        __document__.style.height = __height__ + 'px';
    }

    this.svg = svg;

    return this;
});
