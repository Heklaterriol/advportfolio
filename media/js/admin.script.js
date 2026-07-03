/**
 * Advanced Portfolio Admin Script
 * @copyright  Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license    GNU/GPL (http://www.gnu.org/licenses/gpl-2.0.html)
 */

(function() {
    'use strict';
    document.addEventListener('DOMContentLoaded', function() {
        initTooltips();
        initChosen();
        initDropdowns();
    });

    function initTooltips() {
        var tooltips = document.querySelectorAll('.hasTooltip');
        tooltips.forEach(function(element) {
            var title = element.getAttribute('title');
            if (title) {
                element.addEventListener('mouseenter', function() { showTooltip(element, title); });
                element.addEventListener('mouseleave', function() { hideTooltip(); });
            }
        });
    }

    function showTooltip(element, text) {
        var tooltip = document.createElement('div');
        tooltip.className = 'tooltip fade in';
        tooltip.textContent = text;
        tooltip.style.position = 'absolute';
        tooltip.style.backgroundColor = '#333';
        tooltip.style.color = '#fff';
        tooltip.style.padding = '5px 10px';
        tooltip.style.borderRadius = '3px';
        tooltip.style.zIndex = '1000';
        tooltip.style.fontSize = '12px';
        var rect = element.getBoundingClientRect();
        tooltip.style.top = (rect.top + window.scrollY - tooltip.offsetHeight - 5) + 'px';
        tooltip.style.left = (rect.left + window.scrollX + (rect.width - tooltip.offsetWidth) / 2) + 'px';
        document.body.appendChild(tooltip);
        element._tooltip = tooltip;
    }

    function hideTooltip() {
        var tooltips = document.querySelectorAll('.tooltip');
        tooltips.forEach(function(tooltip) { tooltip.remove(); });
    }

    function initChosen() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.chosen) {
            jQuery('select.chzn-select').chosen();
        }
    }

    function initDropdowns() {
        if (typeof jQuery !== 'undefined' && jQuery.fn.dropdown) {
            jQuery('.dropdown-toggle').dropdown();
        }
    }

    window.Joomla = window.Joomla || {};
    window.Joomla.submitbutton = function(task, form) {
        if (task === 'project.cancel' || (typeof document.formvalidator !== 'undefined' && document.formvalidator.isValid(form))) {
            Joomla.submitform(task, form);
        }
    };

    window.Joomla.submitform = function(task, form) {
        if (typeof form === 'string') { form = document.getElementById(form); }
        if (!form) { return false; }
        var taskInput = document.createElement('input');
        taskInput.type = 'hidden';
        taskInput.name = 'task';
        taskInput.value = task;
        form.appendChild(taskInput);
        form.submit();
        return true;
    };

    window.Joomla.checkAll = function(element) {
        var form = element.form;
        if (!form) { return; }
        var checkboxes = form.querySelectorAll('input[name^="cid"]');
        var checked = element.checked;
        checkboxes.forEach(function(cb) { cb.checked = checked; });
        var boxchecked = form.querySelector('input[name="boxchecked"]');
        if (boxchecked) { boxchecked.value = checked ? checkboxes.length : 0; }
    };
})();