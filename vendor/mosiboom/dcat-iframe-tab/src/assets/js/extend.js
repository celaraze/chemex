$(function () {
    if (window.parent.iframeTabParent && $("a[iframe-extends=true]").length > 0) {
        const iframeTabParent = window.parent.iframeTabParent
        /*其他扩展处理*/
        const iframeTabExtends = {
            /**
             * 添加标签
             * @param element a标签元素
             * @param page_title 页面标题
             * @param icon 默认是圆形
             */
            addTab(element, page_title = '', icon = 'icon-circle') {
                let title = ''
                if (page_title !== '') {
                    title += page_title + '-'
                }
                let page_html = `&nbsp;<i class="fa fa-fw feather ${icon}"></i><p>${title + element.text()}</p>`;
                let url = element.attr('href')
                let id = iframeTabParent.iframeTab.generateID(url);
                if (iframeTabParent.elements.iframe_tab.find(`#iframe-home-${id}`).length > 0) {
                    iframeTabParent.elements.iframe_tab.find(`#iframe-home-${id}`).click()
                    return false
                }
                let choose_element = iframeTabParent.iframeTab.findIframeTabActiveElement()
                iframeTabParent.swiper.appendSlide(iframeTabParent.iframeTabTemplate.tabItem(page_html, id))
                iframeTabParent.elements.iframe_tabContent.append(iframeTabParent.iframeTabTemplate.tabContentItem(url, id))
                iframeTabParent.swiper.updateSlides();
                /*移除tab bar 选中样式*/
                iframeTabParent.iframeTab.removeTabBarStyle()
                /*更新选中缓存中的tab bar*/
                iframeTabParent.iframeTab.cacheUpdateTabBar(choose_element)
                //触发点击
                iframeTabParent.elements.iframe_tab.find(`#iframe-home-${id}`).click()
            },
            init() {
                $(document).on('click', 'a[iframe-tab=true]', function (e) {
                    iframeTabExtends.addTab($(this))
                    e.preventDefault()
                })
            }
        }
        iframeTabExtends.init()
    }
})
