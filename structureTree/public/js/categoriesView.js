$.fn.extend({
    initCategoryView: function () {
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

        /* Load functions */
        this.expandCollapseAllAction(expandIcon, nodesWithChilds, openedNode,
            closedNode, expandClass, collapseClass);
        this.selectCategoryToEdit();
        this.destinationParentToEdit();
        this.selectCategoryToDelete();
        this.initializeValues();
        this.selectOnNodeClick();

        /* After everything is loaded - hide loader */
        this.showLoader();
    },

    initializeValues: function()
    {
        /* Initialize Delete Category which trigger also Edit Category scripts */
        $('#deleteCategoryId').change();
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

            /* Toggling expand - collapse text and icon */
            buttonIcon.toggleClass(expandClass + ' ' + collapseClass);
            if(buttonIcon.hasClass(expandClass))
            {
                buttonIcon.html(' Expand');
            } else {
                buttonIcon.html(' Collapse');
            }
        });
    },

    selectCategoryToEdit: function() {
        /* Edited category select value change */
        $('#editedCategoryId').on('change', function () {
            let hiddenPrevCategory = $('#prevSelectedCategory');
            let selectedCategoryClass = 'selectedCategory';
            let optionSelected = $('option:selected', this);
            let nodeParent = $('#' + optionSelected.val());
            let id = nodeParent.parent().closest('li').attr('id') ? nodeParent.parent().closest('li').attr('id') : 0;

            /* Showing chosen category and his parents */
            nodeParent.parents('li').children('i').removeClass('fa-minus fa-plus').addClass('fa-minus');
            nodeParent.parents('li').children('ul').children('li').show()

            /* Getting all childs IDs and disabling them - cant move category to his own child (with unlocking previous disabled categories) */
            let unlockParent = hiddenPrevCategory.val() ? $('#' + hiddenPrevCategory.val()) : nodeParent;
            unlockParent.find('li').each(function() {
                $('#editedCategoryParent option[value="' + this.id + '"]').removeAttr('disabled');
            });
            if(hiddenPrevCategory.val())
            {
                /* Removing 'selected' class from previous selected option and saving currently chosen category to hidden input */
                $('#' + hiddenPrevCategory.val()).removeClass(selectedCategoryClass);
                $('#editedCategoryParent option[value="' + hiddenPrevCategory.val() + '"]').removeAttr('disabled');
            }
            hiddenPrevCategory.val(optionSelected.val());

            nodeParent.find('li').each(function() {
                $('#editedCategoryParent option[value="' + this.id + '"]').attr('disabled', 'disabled');
            });
            $('#editedCategoryParent option[value="' + optionSelected.val() + '"]').attr('disabled', 'disabled');


            /* Dynamically setting up chosen category name */
            $('#editedCategoryName').val(optionSelected.text().trim());

            /* Setting up default destination category */
            $('#editedCategoryParent').val(id).change();

            /* Adding border if not initial page */
            nodeParent.addClass(selectedCategoryClass);
        });
    },

    destinationParentToEdit: function() {
        /* Changing destination category select */
        $('#editedCategoryParent').on('change', function () {
            /* Different parent element if already 'Main' category has been chosen */
            let nodeParent = ($('option:selected', this).val() != 0) ?
                $('#' + $('option:selected', this).val()).children() : $('#categoryTree');
            let indexInput = $('#editedCategoryIndex');

            /* Clearing select options and appending dynamically new options */
            indexInput.html('');
            let nodeChildrenLength = nodeParent.children().length;
            if(nodeChildrenLength == 0)
            {
                indexInput.append('<option value="0">First</option>');
                return;
            }

            $.each(nodeParent.children(), function () {
                let index = $(this).attr('data-index');
                if (!index)
                {
                    return;
                }

                if(this.id == $('#editedCategoryId').val())
                {
                    indexInput.append('<option value="' + index + '">No change</option>');
                    return;
                }


                indexInput.append($('<option></option>')
                    .attr('value', index)
                    .text($(this).clone().children().remove().end().text().trim())
                );
            });

            if(nodeChildrenLength > 1) {
                indexInput.append('<option value="' + nodeChildrenLength + '">Last</option>');
            }
        });
    },

    selectCategoryToDelete: function() {
        $('#deleteCategoryId').on('change', function () {
            let selectedCategoryId = $(this).val();
            let confirmDeleteInput = $('#confirmDelete');
            let configDeleteInputDiv = $('#deleteHasSubcategories');

            /* Refreshing Edit Category inputs triggering their lib code */
            $('#editedCategoryId').val(selectedCategoryId).change();


            /* Updating deletion modal content when selected category got subcategories */
            if($('#' + selectedCategoryId).children().children().length > 0)
            {
                confirmDeleteInput.prop('required', true);
                configDeleteInputDiv.show();

                return;
            }

            confirmDeleteInput.prop('required', false);
            configDeleteInputDiv.hide();
        });
    },

    selectOnNodeClick: function()
    {
        /* Choose option on click in Delete Category select which trigger also inputs in Edit Category section */
        this.on('click', function (e) {
            $('#deleteCategoryId').val(e.target.id).change();
            $('#addParentId').val(e.target.id).change();
        });
    },
});

/* Initialize tree */
$('#categoryTree').initCategoryView();
