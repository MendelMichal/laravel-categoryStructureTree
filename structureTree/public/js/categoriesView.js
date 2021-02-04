$.fn.extend({
    treeView: function () {
        /* Defining node icons */
        let openedNode = 'fa-minus';
        let closedNode = 'fa-plus';

        /* Defining expand button properties */
        let expandClass = 'fa-angle-down';
        let collapseClass = 'fa-angle-up';
        let expandIcon = $('#expandTree').children('i:first');

        /* Initializing tree view */
        let tree = $(this);
        let nodesWithChilds = tree.find('li').has('ul');

        /* Displaying loader */
        tree.showLoader();

        tree.addClass('tree');
        nodesWithChilds.each(function () {
            let node = $(this);

            /* Adding 'expand' icon when node have childs */
            node.children('i:first').addClass(closedNode)

            node.prepend('');
            node.addClass('node');
            node.on('click', function (e) {
                if (this == e.target) {
                    let icon = $(this).children('i:first');
                    icon.toggleClass(openedNode + ' ' + closedNode);

                    $(this).children().children().toggle();

                    /* Closing all child nodes on parent close click */
                    if(icon.hasClass(closedNode))
                    {
                        $(this).find('li').has('ul').each(function () {
                            $(this).children().children().hide();
                            $(this).children('i').removeClass(openedNode + ' ' + closedNode)
                                .addClass(closedNode);
                        });
                    }

                    /* Check if all nodes are expanded or collapsed */
                    if(nodesWithChilds.length === nodesWithChilds.has('li:visible').length)
                    {
                        // All expanded
                        expandIcon.removeClass(expandClass + ' ' + collapseClass)
                            .addClass(collapseClass).html(' Collapse')
                    } else if(nodesWithChilds.length === nodesWithChilds.has('li:hidden').length
                        || nodesWithChilds.has('li:hidden').length > 0)
                    {
                        // All collapsed
                        expandIcon.removeClass(expandClass + ' ' + collapseClass)
                            .addClass(expandClass).html(' Expand')
                    }

                }
            })

            node.children().children().toggle();
        });

        /* After everything is loaded - hide loader */
        this.showLoader();

        /* Load functions */
        this.expandCollapseAllAction(expandIcon, nodesWithChilds, openedNode,
            closedNode, expandClass, collapseClass);
    },

    showLoader: function () {
        /* Defining blur class and getting loader */
        let blurClass = 'blur';
        let loader = this.closest('.loader');

        if(this.hasClass(blurClass))
        {
            this.removeClass(blurClass);
        } else
        {
            this.addClass(blurClass);
        }

        loader.toggle();
    },

    expandCollapseAllAction: function(buttonIcon, nodesWithChilds, openedIcon,
                                      closedIcon, expandClass, collapseClass) {
        /* Button Clicked */
        $('#expandTree').on('click', function() {
            if(buttonIcon.hasClass(expandClass))
            {
                nodesWithChilds.each(function () {
                    $(this).children().children().show();
                    $(this).children('i').removeClass(openedIcon + ' ' + closedIcon).addClass(openedIcon);
                });
            } else {
                nodesWithChilds.each(function () {
                    $(this).children().children().hide();
                    $(this).children('i').removeClass(openedIcon + ' ' + closedIcon).addClass(closedIcon);
                });
            }

            buttonIcon.toggleClass(expandClass + ' ' + collapseClass);
            if(buttonIcon.hasClass(expandClass))
            {
                buttonIcon.html(' Expand');
            } else {
                buttonIcon.html(' Collapse');
            }
        });
    },
});

/* Initialize tree */
$('#categoryTree').treeView();
