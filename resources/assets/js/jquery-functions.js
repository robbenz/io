;(function ($) {
   /**
   * Will take children and do a expand and collapse on children.
   *
   * @param  {object} args Options
   *   children: {string} Child element to target. Default: li
   *   trigger: {string} Element to trigger the expand and collapse.
   *     Default: .js-trigger
   *   show: {string} Element to show when triggerd. Default: .js-expand
   *   expandedClass: {string} Class added to child when show element is expanded.
   *     Default: js-is-expanded
   *
   * @return void
   */
  $.fn.ioCollapse = function (args) {
    // defaults
    args = $.extend({
      children: 'li',
      trigger: '.js-trigger',
      show: '.js-expand',
      expandedClass: 'js-is-expanded'
    }, args)

    var el = $(this)
    var children = el.find(args.children)
    var trigger = children.find(args.trigger)
    var show = children.find(args.show)

    show.hide()

    trigger.live('click', function () {
      var container = $(this).parents(args.children)

      if (container.hasClass(args.expandedClass)) {
        // close all
        el.find('.' + args.expandedClass).find(args.show).hide()
        el.find('.' + args.expandedClass).removeClass(args.expandedClass)
      } else {
        // close all
        el.find('.' + args.expandedClass).find(args.show).hide()
        el.find('.' + args.expandedClass).removeClass(args.expandedClass)

        // open
        container.find(args.show).show()
        container.addClass(args.expandedClass)
      }
    })
  }

  /**
   * Does a test for mobile device. Returns true for mobile device.
   *
   * @return {Boolean}
   */
  $.fn.is_mobile = function () {
    var isMobile
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
      isMobile = true
    } else {
      isMobile = false
    }

    return isMobile
  }

  /**
   * Global smoooth scroller. NOTE: No click event inside function.
   *
   * @param  {object} options Scroller Options
   *   offset: {int} Offset of the scroll. Default: 0
   *   speed: {int} Speed of scroll. Default: 1000
   *
   *  Example: $('section.main').ioScroller();
   *
   * @return void
   */
  $.fn.ioScroller = function (options) {
    // defaults
    var settings = $.extend({
      offset: 0,
      speed: 1000
    }, options)

    var scrollPos = $(window).scrollTop()
    var target = $(this)

    if (target.length) {
      $('html,body').stop(true, false).animate({
        scrollTop: (target.offset().top - settings.offset) }, settings.speed)
    }
  }

  /**
   * Prints single div content
   *
   * @return void
   */
  $.fn.printDiv = function () {
    var printContents = $(this).html()
    var originalContents = $('body').html()
    $('body').html(printContents)
    $('body').addClass('js-print')
    window.print()
    $('body').html(originalContents)
    $('body').removeClass('js-print')
  }

  /**
   * Sets sets equal heights to all child elements
   *
   * @param  {int} buffer Buffer for equal heights
   *
   * Example: $('#parent').equalHeights(10);
   *
   * @return null
   */
  $.fn.equalHeights = function (buffer) {
    // Defaults
    buffer = buffer || 0

    var parentElement = $(this)

    var maxHeight = 0
    parentElement.children().each(function () {
      if ($(this).outerHeight() > maxHeight) {
        maxHeight = $(this).outerHeight()
      }
    })

    maxHeight = (maxHeight + buffer)

    parentElement.children().each(function () {
      $(this).height(maxHeight)
    })
  }
})(jQuery)
