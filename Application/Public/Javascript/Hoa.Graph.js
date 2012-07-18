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

            return {
                height: bbox.height,
                width : bbox.width,
                x     : bbox.x + translate.x,
                y     : bbox.y + translate.y
            };
        };

        this.move = function ( x, y ) {

            var dx   = null;
            var dy   = null;
            var bbox = that.getBBox();
            var type = that.type;

            if('string' === typeof x) {

                sign = x.charAt(0);

                if('+' === sign || '-' === sign) {

                    dx = parseInt(x);
                    x  = bbox.x + dx;
                }
                else
                    x = parseInt(x);
            }

            if('string' === typeof y) {

                sign = y.charAt(0);

                if('+' === sign || '-' === sign) {

                    dy = parseInt(y);
                    y  = bbox.y + dy;
                }
                else
                    y = parseInt(y);
            }

            if('g' === type || 'path' === type) {

                if(null === dx)
                    dx = x - bbox.x;

                if(null === dy)
                    dy = y - bbox.y;

                if(null === translate)
                    translate = {x: dx, y: dy};
                else {

                    translate.x += dx;
                    translate.y += dy;
                }

                that.attr({
                    transform: 'translate(' +
                        translate.x + ', ' +
                        translate.y +
                    ')'
                });

                return that;
            }

            if('text' === type) {

                if(null !== dx)
                    x += dx + bbox.width / 2;

                if(null !== dy)
                    y += dy + bbox.height / 2;
            }

            that.attr({x: x, y: y});

            return that;
        };

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

    this.circle = function ( cx, cy, r ) {

        var circle = new Element('circle');
        circle.attr({
            cx: cx,
            cy: cy,
            r : r || 10
        });
        svg.append(circle);

        return circle;
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
                        position = iy * columns + ix;

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
            var fx       = fromBbox.x;
            var fy       = fromBbox.y;
            var fw       = fromBbox.width;
            var fh       = fromBbox.height;
            var tx       = toBbox.x;
            var tw       = toBbox.width;
            var fw2      = fw / 2;
            var fh2      = fh / 2;
            var ax       = fx + fw2;
            var by       = toBbox.y + toBbox.height / 2;
            var cx       = tx       + tw            / 2;
            var cy       = fy + fh2;
            var d        = fw2 / (cx - ax) * (cy - by);

            var points = {
                fromX  : fx + fw,
                fromY  : (fy + fh / 2) - d,
                toX    : tx,
                toY    : by + d,
                markerX: 1
            };

            if(points.fromX > points.toX) {

                var dd          = d * 2;
                points.fromX   -= fw;
                points.fromY   += dd;
                points.toX     += tw;
                points.toY     -= dd;
                points.markerX  = -1;
            }

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
                        d: 'M ' + points.fromX  + ',' + points.fromY  +
                           ' C' + points.fromCX + ',' + points.fromCY +
                           ' '  + points.toCX   + ',' + points.toCY   +
                           ' '  + (points.toX - 8 * points.markerX)
                                                + ',' + points.toY
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
