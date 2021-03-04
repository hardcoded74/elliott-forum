// @flow

type InitType = {
    layout: Object,
    settings: Object,
    init?: boolean,
    commonVersion?: string,
    listeners?: Array<Object>,
}

type ChangeType = {
    ele: any,
    width?: string,
    className?: string,

}

const grid = class Grid {
    running:boolean
    listenersAdded:boolean
    minEnableWidth:number
    layout:Object
    nodes:Object
    equalCategories:boolean
    classAdded:boolean
    alwaysCheck:boolean
    newChanges:Array<any>
    globalWidth:number
    cookieArray:Array<Object>
    settings: Object
    rootEle: any
    listeners: Array<Object>
    commonVersion: string
    common: any

    constructor({ layout, settings = {}, listeners, init = false, commonVersion = '20180112' }: InitType) {
        this.running = false;
        this.listenersAdded = false;
        this.minEnableWidth = 1;
        this.layout = layout;
        this.nodes = {};
        this.equalCategories = true;
        this.classAdded = false;
        this.alwaysCheck = true;
        this.newChanges = [];
        this.globalWidth = 1;
        this.cookieArray = [];
        this.settings = Object.assign({
            depth0Selector: '.thNodes__nodeList',
            depth1Selector: '.block',
            depth2Selector: '.node',
            separatorClass: 'thNodes_separator',
        }, settings);

        this.settings.sizes = Object.assign({
            xs: 200,
            sm: 300,
            md: 400,
            lg: 600,
            xl: 1000,
        }, settings.sizes || {});

        this.listeners = listeners || this.populateListeners();
        this.rootEle = null;
        this.commonVersion = commonVersion;
        this.common = window.themehouse.common[commonVersion];

        if (init) {
            this.init();
        }
    }

    // returns array of listeners
    populateListeners() :Array<Object> {
        const listeners = [];
        const sizes = this.settings.sizes;
        const sizeKeys = Object.keys(sizes);
        for (let i = 0, len = sizeKeys.length; i < len; i++) {
            const sizeKey = sizeKeys[i];
            const size = sizes[sizeKey];
            listeners.push({ name: `--below-${sizeKey}`, min: 0, max: size });
            listeners.push({ name: `--above-${sizeKey}`, min: (size + 1), max: 999999 });
            const min = size + 1;
            const max = (i < (len - 1)) ? sizes[sizeKeys[i + 1]] : 999999;
            listeners.push({ name: `--is-${sizeKey}`, min, max });
        }

        return listeners;
    }

    register() :void {
        this.common.register({
            phase: 'initGet',
            addon: 'TH_Nodes',
            func: this.initGet,
            order: 10,
        });
        this.common.register({
            phase: 'initSet',
            addon: 'TH_Nodes',
            func: this.initSet,
            order: 10,
        });
        this.common.register({
            phase: 'resizeGet',
            addon: 'TH_Nodes',
            func: this.resizeGet,
            order: 10,
        });
        this.common.register({
            phase: 'resizeSet',
            addon: 'TH_Nodes',
            func: this.resizeSet,
            order: 10,
        });
    }

    init = () => {
        this.initGet();
        this.initSet();
    }

    initGet = () => {
        this.rootEle = window.document.querySelector(this.settings.depth0Selector);
        if (this.rootEle !== null) {
            this.nodes = this.parse(this.rootEle);

            this.resizeGet();
        } else {
            console.error('Root selector not found');
        }
    }

    initSet = () => {
        this.resizeSet();

        this.rootEle.classList.add('thNodes__nodeList--running');
        this.classAdded = true;
        this.running = true;
    }

    // gets the ID from a className or -1 if none found
    getIdFromClass(replaceString:string, str:string) :number {
        const splitEleClass = str.split(' ');
        for (const classWord of splitEleClass) {
            const replaced = classWord.replace(replaceString, '');
            if (replaced !== classWord) {
                const possibleId = parseInt(replaced, 10);
                if (possibleId > 0) {
                    return possibleId;
                }
            }
        }
        return -1;
    }

    // gets the node ID of an element and optionally only a separator
    getNodeId(ele:any, separatorOnly?:boolean = false):number {
        if (ele !== null) {
            const eleClass = ele.className;
            if (!separatorOnly) {
                if (eleClass.indexOf('block--category') > -1) {
                    return this.getIdFromClass('block--category', eleClass);
                } else if (eleClass.indexOf('node--id') > -1) {
                    return this.getIdFromClass('node--id', eleClass);
                } else {
                    const nearestNode = ele.querySelector(this.settings.depth2Selector);
                    if (nearestNode !== null) {
                        return this.getIdFromClass('node--id', nearestNode.className);
                    }
                }
            }

            if (eleClass.indexOf(this.settings.separatorClass) > -1) {
                return this.getIdFromClass('node--id', eleClass);
            }
        }

        return -1;
    }

    // format layout from addon into object for js
    formatLayout(id:number) :Object {
        const obj = {};
        const layout = this.layout[id];
        if (typeof (layout) !== 'undefined') {
            const {
                fill_last_row,
                max_columns,
                min_column_width,
            } = layout;
            if (fill_last_row && fill_last_row.value) {
                obj.fill = fill_last_row.value;
            }
            if (max_columns && max_columns.value) {
                obj.cols = max_columns.value;
            }
            if (min_column_width && min_column_width.value) {
                obj.width = min_column_width.value;
            }
        }

        return obj;
    }

    // make a fully inherited layout object (inherits from js default, global default, optional custom layout for separators, then specified node)
    makeLayout(id:number, customLayout:Object = {}) :Object {
        const ret = Object.assign({
            cols: 6,
            width: 330,
            fill: 0,
            id,
        }, this.formatLayout(0), customLayout, this.formatLayout(id));

        ret.cols = parseInt(ret.cols, 10);
        ret.width = parseInt(ret.width, 10);
        ret.fill = parseInt(ret.fill, 10);

        if (id === -1) {
            ret.cols = 1; // TODO remove this
            ret.width = 1000;
        }

        return ret;
    }

    // recursive function to return layouts for a parent
    findLayouts(parent: any, parentId:number, parentDepth:number) :Array<Object> {
        const layouts = [];
        const selector = (parentDepth === 0) ? this.settings.depth1Selector : this.settings.depth2Selector;
        const eles = parent.querySelectorAll(selector);
        let layoutNum = 0;

        let lastSeparator = parentId;
        for (let i = 0, len = eles.length; i < len; i++) {
            const ele = eles[i];
            const separator = this.getNodeId(ele.previousElementSibling, true);
            if (separator !== -1) {
                lastSeparator = separator;
                layoutNum += 1;
            }
            const id = this.getNodeId(ele);
            if (typeof (layouts[layoutNum]) === 'undefined') {
                layouts[layoutNum] = {
                    children: [],
                    layout: this.makeLayout(lastSeparator),
                };
            }

            layouts[layoutNum].children.push({
                ele,
                id,
                depth: parentDepth,
                layouts: this.findLayouts(ele, id, parentDepth + 1),
                className: '',
            });
        }

        return layouts;
    }

    // starts recursive generation of node tree
    parse(parent:any, depth:number = 0) :Object {
        const layouts = this.findLayouts(parent, -1, depth);

        if (layouts.length === 0) {
            return {};
        }

        const result = {
            ele: parent,
            id: -1,
            depth,
            className: '',
            ratio: 1,
            layouts,
        };

        //console.log(result);

        return result;
    }

    // recursively calculate changes for node tree
    calculateChanges(nodes:any, depth:number) :any {
        const {
            ratio,
            layouts,
        } = nodes;

        const parentWidth = ratio * this.globalWidth;
        let rowNumAbs = 0;
        let nodeNumAbs = 0;

        for (let layoutNum = 0, layoutLen = layouts.length; layoutNum < layoutLen; layoutNum++) {
            const {
                children,
                layout,
            } = layouts[layoutNum];
            const numChildren = children.length;
            const maxCols = Math.min(Math.floor(parentWidth / layout.width), layout.cols);
            let numCols = (maxCols > 0) ? maxCols : 1;
            const colRemainder = numChildren % maxCols;
            let rowNum = 1;
            const numRows = Math.ceil(numChildren / maxCols);
            let colNum = 0;

            for (let childNum = 0; childNum < numChildren; childNum++) {
                const child = children[childNum];
                const nextRowNum = Math.floor(childNum / maxCols) + 1;
                if (rowNum === nextRowNum) {
                    colNum += 1;
                } else {
                    rowNum = nextRowNum;
                    colNum = 1;
                }
                if (layout.fill === 0) {
                    if (rowNum === numRows && colRemainder > 0) {
                        numCols = colRemainder;
                    }
                } else if (layout.fill === 1) {
                    // don't adjust
                }

                const newWidth = 100 / numCols;
                const newWidthStr = newWidth + '%';

                const newClassName = this.findClassName({
                    colNum,
                    numCols,
                    rowNum,
                    rowNumAbs: rowNumAbs + rowNum,
                    nodeNumAbs,
                    nodeNum: childNum,
                    layoutNum,
                    leftCol: (childNum % numCols === 0),
                    rightCol: (childNum % numCols === (numCols - 1)),
                    numLayouts: layoutLen,
                    width: Math.floor((ratio * newWidth * this.globalWidth) / 100),
                });

                const change:ChangeType = {
                    ele: child.ele,
                };

                if (child.className !== newClassName) {
                    change.className = child.ele.className.replace(child.className, '') + newClassName;
                    child.className = newClassName;
                }
                if (child.width !== newWidthStr) {
                    change.width = newWidthStr;
                    child.width = newWidthStr;
                }
                if (typeof (change.className) !== 'undefined' || typeof (change.width) !== 'undefined') {
                    this.queueChange(change);
                }

                child.ratio = (ratio * newWidth) / 100;
                nodeNumAbs += 1;

                this.calculateChanges(child, depth + 1);
            }
            rowNumAbs += rowNum;
        }
    }

    resize = () => {
        this.resizeGet();
        this.resizeSet();
    }

    resizeGet = () => {
        this.globalWidth = this.rootEle.offsetWidth;
        this.updateGet();
    }

    resizeSet = () => {
        this.updateSet();
    }

    update() :void {
        this.updateGet();
        this.updateSet();
    }

    updateGet() :void {
        this.newChanges = [];
        this.calculateChanges(this.nodes, 0);
    }

    updateSet() :void {
        this.processChanges();
    }

    // adds a change to the queue to make during set
    queueChange(change:Object) :void {
        this.newChanges.push(change);
    }

    // processes queue of changes during set
    processChanges() {
        for (let i = 0, len = this.newChanges.length; i < len; i++) {
            const {
                ele,
                className,
                width
            } = this.newChanges[i];
            if (typeof (className) !== 'undefined') {
                ele.className = className;
            }
            if (typeof (width) !== 'undefined') {
                ele.style.width = width;
                ele.style.flexBasis = width;
                ele.style.maxWidth = width;
            }
        }
    }

    // calculates a className for a given node's state
    findClassName(config:Object) :string {
        const {
            colNum,
            numCols,
            rowNum,
            rowNumAbs,
            nodeNum,
            nodeNumAbs,
            leftCol,
            rightCol,
            layoutNum,
            numLayouts,
            width,
        } = config;
        const prefix = 'th_nodes';

        let className = ` ${prefix}`;
        if (width < 100) {
            className = `${className} ${prefix}_active`;
        }
        if (leftCol) {
            className = `${className} ${prefix}_left`;
        }
        if (rightCol) {
            className = `${className} ${prefix}_right`;
        }
        className = `${className} ${prefix}_col_${colNum}`;
        className = `${className} ${prefix}_numCols_${numCols}`;
        className = `${className} ${prefix}_row_${rowNum}`;
        className = `${className} ${prefix}_rowAbs_${rowNumAbs}`;
        className = `${className} ${prefix}_layNum_${layoutNum}`;
        className = `${className} ${prefix}_numLays_${numLayouts}`;
        className = `${className} ${prefix}_node_${nodeNum}`;
        className = `${className} ${prefix}_nodeAbs_${nodeNumAbs}`;
        className = `${className} ${prefix}_row_${(rowNumAbs % 2 === 1) ? 'odd' : 'even'}`;

        // element query classes
        const listeners = this.listeners;
        if (typeof (listeners) !== 'undefined') {
            for (let i = 0, len = listeners.length; i < len; i++) {
                if (width < listeners[i].max && width >= listeners[i].min) {
                    className = `${className} ${prefix}${listeners[i].name}`;
                }
            }
        }

        return className;
    }
};

if (typeof (window.themehouse) === 'undefined') {
    window.themehouse = {};
}

if (typeof (window.themehouse.nodes) === 'undefined') {
    window.themehouse.nodes = {};
}

window.themehouse.nodes.grid = grid;

export default grid;
