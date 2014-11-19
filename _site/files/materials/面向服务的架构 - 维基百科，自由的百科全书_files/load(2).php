mw.loader.implement("ext.uls.init",function($,jQuery){(function($,mw){'use strict';if(mw.hook===undefined){mw.hook=(function(){var lists={},slice=Array.prototype.slice;return function(name){var list=lists[name]||(lists[name]=$.Callbacks('memory'));return{add:list.add,remove:list.remove,fire:function(){return list.fireWith(null,slice.call(arguments));}};};}());}mw.uls=mw.uls||{};mw.uls.previousLanguagesCookie='uls-previous-languages';mw.uls.previousLanguageAutonymCookie='uls-previous-language-autonym';mw.uls.languageSettingsModules=['ext.uls.inputsettings','ext.uls.displaysettings'];mw.uls.languageSelectionMethod=undefined;mw.uls.addEventLoggingTriggers=function(){mw.uls.languageSelectionMethod=undefined;$('#uls-map-block').on('click',function(){mw.uls.languageSelectionMethod='map';});$('#uls-languagefilter').on('keydown',function(){if($(this).val()===''){mw.uls.languageSelectionMethod='search';}});$('#uls-lcd-quicklist a').on('click',function(){mw.uls.languageSelectionMethod='common';}
);};mw.uls.changeLanguage=function(language){var uri=new mw.Uri(window.location.href),deferred=new $.Deferred();deferred.done(function(){uri.extend({setlang:language});window.location.href=uri.toString();});mw.hook('mw.uls.interface.language.change').fire(language,deferred);window.setTimeout(function(){deferred.resolve();},mw.config.get('wgULSEventLogging')*500);};mw.uls.setPreviousLanguages=function(previousLanguages){$.cookie(mw.uls.previousLanguagesCookie,JSON.stringify(previousLanguages),{path:'/'});};mw.uls.getPreviousLanguages=function(){var previousLanguages=$.cookie(mw.uls.previousLanguagesCookie);if(!previousLanguages){return[];}return JSON.parse(previousLanguages).slice(-5);};mw.uls.getBrowserLanguage=function(){return(window.navigator.language||window.navigator.userLanguage||'').split('-')[0];};mw.uls.getCountryCode=function(){return window.Geo&&(window.Geo.country||window.Geo.country_code);};mw.uls.getAcceptLanguageList=function(){return mw.config.get(
'wgULSAcceptLanguageList')||[];};mw.uls.getFrequentLanguageList=function(countryCode){var unique=[],list=[mw.config.get('wgUserLanguage'),mw.config.get('wgContentLanguage'),mw.uls.getBrowserLanguage()].concat(mw.uls.getPreviousLanguages()).concat(mw.uls.getAcceptLanguageList());countryCode=countryCode||mw.uls.getCountryCode();if(countryCode){list=list.concat($.uls.data.getLanguagesInTerritory(countryCode));}$.each(list,function(i,v){if($.inArray(v,unique)===-1){unique.push(v);}});unique=$.grep(unique,function(langCode){var target;if($.fn.uls.defaults.languages[langCode]!==undefined){return true;}target=$.uls.data.isRedirect(langCode);if(target){return $.fn.uls.defaults.languages[target]!==undefined;}return false;});return unique;};function isBrowserSupported(){var blacklist={'msie':[['<=',7]]};if(parseInt(mw.config.get('wgVersion').split('.')[1],'10')<22){return!/MSIE [67]/i.test(navigator.userAgent);}return!$.client.test(blacklist,null,true);}mw.uls.init=function(callback){if(!
isBrowserSupported()){$('#pt-uls').hide();return;}if(callback){callback.call(this);}};$(document).ready(function(){mw.uls.init();});}(jQuery,mediaWiki));},{"css":[".uls-menu a{cursor:pointer}.uls-menu.callout .caret-before{border-top:20px solid transparent;border-right:20px solid #C9C9C9;border-bottom:20px solid transparent;display:inline-block;left:-21px;top:30px;position:absolute}.uls-menu.callout .caret-after{border-top:20px solid transparent;border-right:20px solid #FCFCFC;border-bottom:20px solid transparent;display:inline-block;left:-20px;top:30px;position:absolute}.uls-ui-languages button{width:23%;text-overflow:ellipsis;margin-right:4%}button.uls-more-languages{width:auto}.settings-title{font-size:11pt}.settings-text{color:#555555;font-size:9pt}div.display-settings-block:hover .settings-text{color:#252525}\n/* cache key: zhwiki:resourceloader:filter:minify-css:7:22d1681fa868b4ff4fbcb1ec1e58a9ea */"]},{},{});mw.loader.implement("ext.uls.interface",function($,jQuery){(function($,
mw){'use strict';function displaySettings(){var $displaySettingsTitle,displaySettingsText,$displaySettings;displaySettingsText=$.i18n('ext-uls-display-settings-desc');$displaySettingsTitle=$('<div data-i18n="ext-uls-display-settings-title">').addClass('settings-title').attr('title',displaySettingsText);$displaySettings=$('<div>').addClass('display-settings-block').prop('id','display-settings-block').append($displaySettingsTitle.i18n());return $displaySettings;}function inputSettings(){var $inputSettingsTitle,inputSettingsText,$inputSettings;inputSettingsText=$.i18n('ext-uls-input-settings-desc');$inputSettingsTitle=$('<div data-i18n="ext-uls-input-settings-title">').addClass('settings-title').attr('title',inputSettingsText);$inputSettings=$('<div>').addClass('input-settings-block').prop('id','input-settings-block').append($inputSettingsTitle.i18n());return $inputSettings;}function addDisplaySettings(uls){var $displaySettings=displaySettings();uls.$menu.find('#uls-settings-block').
append($displaySettings);$displaySettings.on('click',function(){var languagesettings=$displaySettings.data('languagesettings'),displaySettingsOptions={defaultModule:'display'},ulsPosition=mw.config.get('wgULSPosition'),anonMode=(mw.user.isAnon()&&!mw.config.get('wgULSAnonCanChangeLanguage'));if(!languagesettings){if(ulsPosition==='personal'&&!anonMode){displaySettingsOptions.onClose=function(){uls.show();};}$.extend(displaySettingsOptions,uls.position());mw.loader.using(mw.uls.languageSettingsModules,function(){$displaySettings.languagesettings(displaySettingsOptions).click();});}mw.hook('mw.uls.settings.open').fire('uls');uls.hide();});}function addInputSettings(uls){var $inputSettings=inputSettings();uls.$menu.find('#uls-settings-block').append($inputSettings);$inputSettings.on('click',function(){var position=uls.position(),languagesettings=$inputSettings.data('languagesettings');if(!languagesettings){mw.loader.using(mw.uls.languageSettingsModules,function(){$inputSettings.
languagesettings({defaultModule:'input',onClose:function(){uls.show();},top:position.top,left:position.left}).click();});}mw.hook('mw.uls.settings.open').fire('uls');uls.hide();});}function addAccessibilityFeatures($target){$target.attr({tabIndex:0,role:'button','aria-haspopup':true});$target.click(function(){$(this).css('outline','none');});$target.blur(function(){$(this).css('outline','');});$target.keydown(function(event){if(event.keyCode===13){$(this).click();event.preventDefault();event.stopPropagation();}});}function showULSTooltip(){var ulsPosition=mw.config.get('wgULSPosition'),currentLang=mw.config.get('wgUserLanguage'),previousLang,previousLanguageAutonym,$ulsTrigger,anonMode,rtlPage=$('body').hasClass('rtl'),tipsyGravity={personal:'n',interlanguage:rtlPage?'e':'w'},previousLanguages=mw.uls.getPreviousLanguages()||[];previousLang=previousLanguages.slice(-1)[0];$ulsTrigger=(ulsPosition==='interlanguage')?$('.uls-settings-trigger'):$('.uls-trigger');if(previousLang===
currentLang){$ulsTrigger.tipsy({gravity:rtlPage?'e':'w'});return;}previousLanguages.push(currentLang);mw.uls.setPreviousLanguages(previousLanguages);anonMode=(mw.user.isAnon()&&!mw.config.get('wgULSAnonCanChangeLanguage'));if(anonMode||!previousLang){return;}previousLanguageAutonym=$.cookie(mw.uls.previousLanguageAutonymCookie)||previousLang;$ulsTrigger.tipsy({gravity:tipsyGravity[ulsPosition],delayOut:3000,html:true,fade:true,trigger:'manual',title:function(){var link;link=$('<a>').text(previousLanguageAutonym).attr({href:'#','class':'uls-prevlang-link',lang:previousLang,dir:'auto'});link=$('<div>').html(link).html();return mw.msg('ext-uls-undo-language-tooltip-text',link);}});$.cookie(mw.uls.previousLanguageAutonymCookie,mw.config.get('wgULSCurrentAutonym'),{path:'/'});function showTipsy(timeout){var tipsyTimer=0;$ulsTrigger.tipsy('show');$('.tipsy').on('mouseover',function(){window.clearTimeout(tipsyTimer);});$('.tipsy').on('mouseout',function(){tipsyTimer=window.setTimeout(
hideTipsy,timeout);});$('.tipsy').on('click',hideTipsy);$('a.uls-prevlang-link').on('click.ulstipsy',function(event){var deferred=$.Deferred();event.preventDefault();deferred.done(function(){mw.uls.changeLanguage(event.target.lang);});mw.hook('mw.uls.language.revert').fire(deferred);window.setTimeout(function(){deferred.resolve();},mw.config.get('wgULSEventLogging')*500);});tipsyTimer=window.setTimeout(hideTipsy,timeout);}function hideTipsy(){$ulsTrigger.tipsy('hide');}window.setTimeout(function(){showTipsy(6000);},700);$ulsTrigger.on('mouseover',function(){if(!$('.uls-menu:visible').length){showTipsy(3000);}});}$(document).ready(function(){mw.uls.init(function(){var $triggers,$pLang,$ulsTrigger=$('.uls-trigger'),rtlPage=$('body').hasClass('rtl'),anonMode=(mw.user.isAnon()&&!mw.config.get('wgULSAnonCanChangeLanguage')),imeSelector=mw.config.get('wgULSImeSelectors').join(', '),ulsPosition=mw.config.get('wgULSPosition');if(ulsPosition==='interlanguage'){$pLang=$('#p-lang');$ulsTrigger=$(
'<span>').addClass('uls-settings-trigger');$pLang.show().prepend($ulsTrigger);$ulsTrigger=$('.uls-settings-trigger');$pLang.find('.uls-p-lang-dummy').remove();if(!$pLang.find('div ul').children().length){$pLang.find('h3').text(mw.msg('uls-plang-title-languages'));}$ulsTrigger.attr({title:mw.msg('ext-uls-select-language-settings-icon-tooltip')});$ulsTrigger.on('click',function(e,eventParams){var languagesettings=$ulsTrigger.data('languagesettings'),languageSettingsOptions;if(languagesettings){if(!languagesettings.shown){mw.hook('mw.uls.settings.open').fire(eventParams&&eventParams.source||'interlanguage');}}else{languageSettingsOptions={defaultModule:'display',onVisible:function(){var topRowHeight,caretHeight,caretWidth,$caretBefore=$('<span>').addClass('caret-before'),$caretAfter=$('<span>').addClass('caret-after'),ulsTriggerWidth=this.$element.width(),ulsTriggerOffset=this.$element.offset();this.$window.addClass('callout');this.$window.prepend($caretBefore,$caretAfter);if(rtlPage){
caretWidth=parseInt($caretBefore.css('border-left-width'),10);this.left=ulsTriggerOffset.left-this.$window.width()-caretWidth;}else{caretWidth=parseInt($caretBefore.css('border-right-width'),10);this.left=ulsTriggerOffset.left+ulsTriggerWidth+caretWidth;}topRowHeight=this.$window.find('.row').height();caretHeight=parseInt($caretBefore.css('top'),10);this.top=ulsTriggerOffset.top-topRowHeight-caretHeight/2;this.position();}};mw.loader.using(mw.uls.languageSettingsModules,function(){$ulsTrigger.languagesettings(languageSettingsOptions).click();});e.stopPropagation();}});}else if(anonMode){$ulsTrigger.on('click',function(e,eventParams){var languagesettings=$ulsTrigger.data('languagesettings');e.preventDefault();if(languagesettings){if(!languagesettings.shown){mw.hook('mw.uls.settings.open').fire(eventParams&&eventParams.source||'personal');}}else{mw.loader.using(mw.uls.languageSettingsModules,function(){$ulsTrigger.languagesettings();$ulsTrigger.trigger('click',eventParams);});}});}else{
$ulsTrigger.on('click',function(e,eventParams){var uls=$ulsTrigger.data('uls');e.preventDefault();if(uls){if(!uls.shown){mw.hook('mw.uls.settings.open').fire(eventParams&&eventParams.source||'personal');}}else{mw.loader.using('ext.uls.mediawiki',function(){$ulsTrigger.uls({quickList:function(){return mw.uls.getFrequentLanguageList();},onReady:function(){var uls=this;mw.loader.using(mw.uls.languageSettingsModules,function(){addDisplaySettings(uls);addInputSettings(uls);});},onSelect:function(language){mw.uls.changeLanguage(language);},onVisible:function(){mw.uls.addEventLoggingTriggers();}});window.setTimeout(function(){$ulsTrigger.trigger('click',eventParams);},0);});}});}$triggers=$('.uls-settings-trigger, .uls-trigger');addAccessibilityFeatures($triggers);$('#uls-preferences-link').text(mw.msg('ext-uls-language-settings-preferences-link')).click(function(){$ulsTrigger.trigger('click',{source:'preferences'});return false;});showULSTooltip();$('body').on('focus.imeinit',imeSelector,
function(){var $input=$(this);$('body').off('.imeinit');mw.loader.using('ext.uls.ime',function(){mw.ime.setup();mw.ime.handleFocus($input);});});});});}(jQuery,mediaWiki));},{},{"ext-uls-language-settings-preferences-link":"\u66f4\u591a\u8bed\u8a00\u8bbe\u7f6e","ext-uls-select-language-settings-icon-tooltip":"\u8bed\u8a00\u8bbe\u7f6e","ext-uls-undo-language-tooltip-text":"\u8bed\u8a00\u5df2\u66f4\u6539\u81ea$1","uls-plang-title-languages":"\u8bed\u8a00"},{});mw.loader.implement("ext.visualEditor.viewPageTarget.init",function($,jQuery){(function(){var conf,tabMessages,uri,pageExists,viewUri,veEditUri,isViewPage,init,support,targetPromise,enable,userPrefEnabled,plugins=[];function getTarget(){if(!targetPromise){targetPromise=mw.loader.using('ext.visualEditor.viewPageTarget').then(function(){var target=new ve.init.mw.ViewPageTarget();if(mw.track){ve.trackSubscribeAll(function(topic,data){mw.track.call(null,'ve.'+topic,data);});}ve.init.mw.ViewPageTarget.prototype.setupSectionEditLinks=
init.setupSectionLinks;target.addPlugins(plugins);return target;},function(e){mw.log.warning('VisualEditor failed to load: '+e);});}return targetPromise;}conf=mw.config.get('wgVisualEditorConfig');tabMessages=conf.tabMessages;uri=new mw.Uri();pageExists=!!mw.config.get('wgArticleId')||mw.config.get('wgNamespaceNumber')<0;viewUri=new mw.Uri(mw.util.getUrl(mw.config.get('wgRelevantPageName')));veEditUri=viewUri.clone().extend({veaction:'edit'});isViewPage=(mw.config.get('wgIsArticle')&&!('diff'in uri.query));support={es5:!!(Array.isArray&&Array.prototype.filter&&Array.prototype.indexOf&&Array.prototype.map&&Date.now&&Date.prototype.toJSON&&Object.create&&Object.keys&&String.prototype.trim&&window.JSON&&JSON.parse&&JSON.stringify&&Function.prototype.bind),contentEditable:'contentEditable'in document.createElement('div'),svg:!!(document.createElementNS&&document.createElementNS('http://www.w3.org/2000/svg','svg').createSVGRect)};init={support:support,blacklist:conf.blacklist,addPlugin:
function(plugin){plugins.push(plugin);},setupSkin:function(){init.setupTabs();init.setupSectionLinks();},setupTabs:function(){if(mw.config.get('wgNamespaceIds')[true&&'education_program']===mw.config.get('wgNamespaceNumber')){return;}var caVeEdit,action=pageExists?'edit':'create',pTabsId=$('#p-views').length?'p-views':'p-cactions',$caSource=$('#ca-viewsource'),$caEdit=$('#ca-edit'),$caVeEdit=$('#ca-ve-edit'),$caEditLink=$caEdit.find('a'),$caVeEditLink=$caVeEdit.find('a'),reverseTabOrder=$('body').hasClass('rtl')&&pTabsId==='p-views',caVeEditNextnode=(reverseTabOrder^conf.tabPosition==='before')?$caEdit.get(0):$caEdit.next().get(0);if(!$caVeEdit.length){if($caEdit.length&&!$caSource.length){caVeEdit=mw.util.addPortletLink(pTabsId,veEditUri,tabMessages[action]!==null?mw.msg(tabMessages[action]):$caEditLink.text(),'ca-ve-edit',mw.msg('tooltip-ca-ve-edit'),mw.msg('accesskey-ca-ve-edit'),caVeEditNextnode);$caVeEdit=$(caVeEdit);$caVeEditLink=$caVeEdit.find('a');}}else if($caEdit.length&&
$caVeEdit.length){if(reverseTabOrder^conf.tabPosition==='before'){if($caEdit[0].nextSibling===$caVeEdit[0]){$caVeEdit.after($caEdit);}}else{if($caVeEdit[0].nextSibling===$caEdit[0]){$caEdit.after($caVeEdit);}}if(tabMessages[action]!==null){$caVeEditLink.text(mw.msg(tabMessages[action]));}}if(!(init.isAvailable&&userPrefEnabled)){$caVeEdit.remove();}if($('#ca-view-foreign').length){if(tabMessages[action+'localdescriptionsource']!==null){$caEditLink.text(mw.msg(tabMessages[action+'localdescriptionsource']));}}else{if(tabMessages[action+'source']!==null){$caEditLink.text(mw.msg(tabMessages[action+'source']));}}if(conf.tabPosition==='before'){$caEdit.addClass('collapsible');}else{$caVeEdit.addClass('collapsible');}if(tabMessages[action+'appendix']!==null){$caVeEditLink.append($('<span>').addClass('ve-tabmessage-appendix').text(mw.msg(tabMessages[action+'appendix'])));}if(tabMessages[action+'sourceappendix']!==null){$caEditLink.append($('<span>').addClass('ve-tabmessage-appendix').text(mw.
msg(tabMessages[action+'sourceappendix'])));}if(isViewPage){$caVeEdit.click(init.onEditTabClick);}},setupSectionLinks:function(){var $editsections=$('#mw-content-text .mw-editsection'),bodyDir=$('body').css('direction');if($editsections.css('direction')!==bodyDir){$editsections.css('direction',bodyDir);}if($editsections.find('.mw-editsection-visualeditor').length===0){$editsections.each(function(){var $editsection=$(this),$editSourceLink=$editsection.find('a').eq(0),$editLink=$editSourceLink.clone(),$divider=$('<span>'),dividerText=mw.msg('pipe-separator');if(tabMessages.editsectionsource!==null){$editSourceLink.text(mw.msg(tabMessages.editsectionsource));}if(tabMessages.editsection!==null){$editLink.text(mw.msg(tabMessages.editsection));}$divider.addClass('mw-editsection-divider').text(dividerText);if(!$('#ca-view-foreign').length){$editLink.attr('href',function(i,val){return new mw.Uri(veEditUri).extend({vesection:new mw.Uri(val).query.section});}).addClass(
'mw-editsection-visualeditor');if(conf.tabPosition==='before'){$editSourceLink.before($editLink,$divider);}else{$editSourceLink.after($divider,$editLink);}}});}if(tabMessages.editsectionappendix){$editsections.find('.mw-editsection-visualeditor').append($('<span>').addClass('ve-tabmessage-appendix').text(mw.msg(tabMessages.editsectionappendix)));}if(tabMessages.editsectionsourceappendix){$editsections.find('a:not(.mw-editsection-visualeditor)').append($('<span>').addClass('ve-tabmessage-appendix').text(mw.msg(tabMessages.editsectionsourceappendix)));}if(isViewPage){$editsections.find('.mw-editsection-visualeditor').click(init.onEditSectionLinkClick);}},onEditTabClick:function(e){if((e.which&&e.which!==1)||e.shiftKey||e.altKey||e.ctrlKey||e.metaKey){return;}var $spinner=$('<div class="mw-viewPageTarget-loading"></div>');$('#firstHeading').prepend($spinner);if(window.history.pushState&&uri.query.veaction!=='edit'){uri=veEditUri;window.history.pushState({tag:'visualeditor'},document.title
,uri);}e.preventDefault();getTarget().done(function(target){ve.track('Edit',{action:'edit-link-click'});target.activate();}).always(function(){$spinner.remove();});},onEditSectionLinkClick:function(e){if((e.which&&e.which!==1)||e.shiftKey||e.altKey||e.ctrlKey||e.metaKey){return;}var $spinner=$('<div class="mw-viewPageTarget-loading"></div>');$('#firstHeading').prepend($spinner);if(window.history.pushState&&uri.query.veaction!=='edit'){window.history.pushState({tag:'visualeditor'},document.title,this.href);}e.preventDefault();getTarget().done(function(target){ve.track('Edit',{action:'section-edit-link-click'});target.saveEditSection($(e.target).closest('h1, h2, h3, h4, h5, h6').get(0));target.activate();}).always(function(){$spinner.remove();});}};support.visualEditor=support.es5&&support.contentEditable&&support.svg&&(('vewhitelist'in uri.query)||!$.client.test(init.blacklist,null,true));enable=mw.user.options.get('visualeditor-enable',conf.defaultUserOptions.enable);userPrefEnabled=(!
(conf.disableForAnons&&mw.config.get('wgUserName')===null)&&(mw.config.get('wgUserName')===null?(conf.defaultUserOptions.enable&&!conf.defaultUserOptions.betatempdisable):(enable&&enable!=='0'&&!mw.user.options.get('visualeditor-betatempdisable',conf.defaultUserOptions.betatempdisable))));init.isAvailable=(support.visualEditor&&$.inArray(mw.config.get('skin'),conf.skins)!==-1&&$.inArray(new mw.Title(mw.config.get('wgRelevantPageName')).getNamespaceId(),conf.namespaces)!==-1&&mw.config.get('wgTranslatePageTranslation')!=='translation'&&mw.config.get('wgPageContentModel')==='wikitext');mw.libs.ve=init;if(init.isAvailable&&userPrefEnabled){$('html').addClass('ve-available');}else{$('html').addClass('ve-not-available');}$(function(){if(init.isAvailable){if(isViewPage&&uri.query.veaction==='edit'){var $spinner=$('<div class="mw-viewPageTarget-loading"></div>');$('#firstHeading').prepend($spinner);getTarget().done(function(target){target.activate().always(function(){$spinner.remove();});});}
}if(userPrefEnabled){init.setupSkin();}});}());},{"css":[
".mw-viewPageTarget-loading{width:128px;height:15px;float:right} .mw-editsection{white-space:nowrap; unicode-bidi:-moz-isolate;unicode-bidi:-webkit-isolate;unicode-bidi:isolate}.mw-editsection-divider{color:#555}.ve-tabmessage-appendix{font-size:0.7em;vertical-align:top;line-height:1.43em;padding-left:0.5em; background-image:none !important;display:inline !important} .mw-viewPageTarget-loading{background-image:url(data:image/gif;base64,R0lGODlhgAAPAPEAAP///6fX+eXy/KfX+SH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAgAAPAAACo5QvoIC33NKKUtF3Z8RbN/55CEiNonMaJGp1bfiaMQvBtXzTpZuradUDZmY+opA3DK6KwaQTCbU9pVHc1LrDUrfarq765Ya9u+VRzLyO12lwG10yy39zY11Jz9t/6jf5/HfXB8hGWKaHt6eYyDgo6BaH6CgJ+QhnmWWoiVnI6ddJmbkZGkgKujhplNpYafr5OooqGst66Uq7OpjbKmvbW/p7UAAAIfkECQoAAAAsAAAAAIAADwAAArCcP6Ag7bLYa3HSZSG2le/Zgd8TkqODHKWzXkrWaq83i7V5s6cr2f2TMsSGO9lPl+PBisSkcekMJphUZ/OopGGfWug2Jr16x92yj3w247bh6teNXseRbyvc0rbr6/x5Ng0op4YSJDb4JxhI58eliEiYYujYmFi5eEh5OZnXhylp+RiaKQpWeDf5qQk6yprawMno2nq6KlsaSauqS5rLu8cI69k7+ytcvGl6XDtsyzxcAAAh+QQJCgAAACwAAAAAgAAPAAACvpw/oIC3IKIUb8pq6cpacWyBk3htGRk1xqMmZviOcemdc4R2kF3DvfyTtFiqnPGm+yCPQdzy2RQMF9Moc+fDArU0rtMK9SYzVUYxrASrxdc0G00+K8ruOu+9tmf1W06ZfsfXJfiFZ0g4ZvEndxjouPfYFzk4mcIICJkpqUnJWYiYs9jQVpm4edqJ+lkqikDqaZoquwr7OtHqAFerqxpL2xt6yQjKO+t7bGuMu1L8a5zsHI2MtOySVwo9fb0bVQAAIfkECQoAAAAsAAAAAIAADwAAAsucP6CAt9zSErSKZyvOd/KdgZaoeaFpRZKiPi1aKlwnfzBF4jcNzDk/e7EiLuLuhzwqayfmaNnjCCGNYhXqw9qcsWjT++TqxIKp2UhOprXf7PoNrpyvQ3p8fAdu82o+O5w3h2A1+Nfl5geHuLgXhEZVWBeZSMnY1oh5qZnyKOhgiGcJKHqYOSrVmWpHGmpauvl6CkvhaUD4qejaOqvH2+doV7tSqdsrexybvMsZrDrJaqwcvSz9i9qM/Vxs7Qs6/S18a+vNjUx9/v1TAAAh+QQJCgAAACwAAAAAgAAPAAAC0Zw/oIC33NKKUomLxct4c718oPV5nJmhGPWwU9TCYTmfdXp3+aXy+wgQuRRDSCN2/PWAoqVTCSVxilQZ0RqkSXFbXdf3ZWqztnA1eUUbEc9wm8yFe+VguniKPbNf6mbU/ubn9ieUZ6hWJAhIOKbo2Pih58C3l1a5OJiJuflYZidpgHSZCOnZGXc6l3oBWrE2aQnLWYpKq2pbV4h4OIq1eldrigt8i7d73Ns3HLjMKGycHC1L+hxsXXydO9wqOu3brPnLXL3C640sK+6cTaxNflEAACH5BAkKAAAALAAAAACAAA8AAALVnD+ggLfc0opS0SeyFnjn7oGbqJHf4mXXFD2r1bKNyaEpjduhPvLaC5nJEK4YTKhI1ZI334m5g/akJacAiDUGiUOHNUd9ApTgcTN81WaRW++Riy6Tv/S4dQ1vG4ps4NwOaBYlOEVYhYbnplexyJf3ZygGOXkWuWSZuNel+aboV0k5GFo4+qN22of6CMoq2kr6apo6m5fJWCoZm+vKu2Hr6KmqiHtJLKebRhuszNlYZ3ncewh9J9z8u3mLHA0rvetrzYjd2Wz8bB6oNO5MLq6FTp2+bVUAACH5BAkKAAAALAAAAACAAA8AAALanD+ggLfc0opS0XeX2Fy8zn2gp40ieHaZFWHt9LKNO5eo3aUhvisj6RutIDUZgnaEFYnJ4M2Z4210UykQ8BtqY0yHstk1UK+/sdk63i7VYLYX2sOa0HR41S5wi7/vcMWP1FdWJ/dUGIWXxqX3xxi4l0g4GEl5yOHIBwmY2cg1aXkHSjZXmbV4uoba5kkqelbaapo6u0rbN/SZG7trKFv7e6savKTby4voaoVpNAysiXscV4w8fSn8fN1pq1kd2j1qDLK8yYy9/ff9mgwrnv2o7QwvGO1ND049UgAAIfkECQoAAAAsAAAAAIAADwAAAticP6CAt9zSilLRd2d8onvBfV0okp/pZdamNRi7ui3yyoo4Ljio42h+w6kgNiJt5kAaasdYE7D78YKlXpX6GWphxqTT210qK1Cf9XT2SKXbYvv5Bg+jaWD5ekdjU9y4+PsXRuZHRrdnZ5inVidAyCTXF+nGlVhpdjil2OE49hjICVh4qZlpibcDKug5KAlHOWqqR8rWCjl564oLFruIucaYGlz7+XoKe2wsIqxLzMxaxIuILIs6/JyLbZsdGF063Uu6vH2tXc79LZ1MLWS96t4JH/rryzhPWgAAIfkECQoAAAAsAAAAAIAADwAAAtWcP6CAt9zSilLRd2fEe4kPCk8IjqTonZnVsQ33arGLwLV8Kyeqnyb5C60gM2LO6MAlaUukwdbcBUspYFXYcla00KfSywRzv1vpldqzprHFoTv7bsOz5jUaUMer5vL+Mf7Hd5RH6HP2AdiUKLa41Tj1Acmjp0bJFuinKKiZyUhnaBd5OLnzSNbluOnZWQZqeVdIYhqWyop6ezoquTs6O0aLC5wrHErqGnvJibms3LzKLIYMe7xnO/yL7TskLVosqa1aCy3u3FrJbSwbHpy9fr1NfR4fUgAAIfkECQoAAAAsAAAAAIAADwAAAsqcP6CAt9zSilLRd2fEW7cnhKIAjmFpZla3fh7CuS38OrUR04p5Ljzp46kgMqLOaJslkbhbhfkc/lAjqmiIZUFzy2zRe5wGTdYQuKs9N5XrrZPbFu94ZYE6ms5/9cd7/T824vdGyIa3h9inJQfA+DNoCHeomIhWGUcXKFIH6RZZ6Bna6Zg5l8JnSamayto2WtoI+4jqSjvZelt7+URKpmlmKykM2vnqa1r1axdMzPz5LLooO326Owxd7Bzam4x8pZ1t3Szu3VMOdF4AACH5BAkKAAAALAAAAACAAA8AAAK/nD+ggLfc0opS0XdnxFs3/i3CSApPSWZWt4YtAsKe/DqzXRsxDqDj6VNBXENakSdMso66WzNX6fmAKCXRasQil9onM+oziYLc8tWcRW/PbGOYWupG5Tsv3TlXe9/jqj7ftpYWaPdXBzbVF2eId+jYCAn1KKlIApfCSKn5NckZ6bnJpxB2t1kKinoqJCrlRwg4GCs4W/jayUqamaqryruES2b72StsqgvsKlurDEvbvOx8mzgazNxJbD18PN1aUgAAIfkECQoAAAAsAAAAAIAADwAAArKcP6CAt9zSilLRd2fEWzf+ecgjlKaQWZ0asqPowAb4urE9yxXUAqeZ4tWEN2IOtwsqV8YkM/grLXvTYbV4PTZpWGYU9QxTxVZyd4wu975ZZ/qsjsPn2jYpatdx62b+2y8HWMTW5xZoSIcouKjYePeTh7TnqFcpabmFSfhHeemZ+RkJOrp5OHmKKapa+Hiyyokaypo6q1CaGDv6akoLu3DLmLuL28v7CdypW6vsK9vsE1UAACH5BAkKAAAALAAAAACAAA8AAAKjnD+ggLfc0opS0XdnxFs3/nkISI2icxokanVt+JoxC8G1fNOlm6tp1QNmZj6ikDcMrorBpBMJtT2lUdzUusNSt9qurvrlhr275VHMvI7XaXAbXTLLf3NjXUnP23/qN/n8d9cHyEZYpoe3p5jIOCjoFofoKAn5CGeZZaiJWcjp10mZuRkaSAq6OGmU2lhp+vk6iioay3rpSrs6mNsqa9tb+ntQAAA7AAAAAAAAAAAA);background-image:url(//bits.wikimedia.org/static-1.25wmf7/extensions/VisualEditor/modules/ve-mw/init/styles/images/loading-ltr.gif?2014-11-05T19:40:00Z)!ie}\n/* cache key: zhwiki:resourceloader:filter:minify-css:7:1085b9e0720fe0ccf0df400a77fe26cc */"
]},{"accesskey-ca-editsource":"e","accesskey-ca-ve-edit":"v","accesskey-save":"s","pipe-separator":" | ","tooltip-ca-createsource":"\u521b\u5efa\u672c\u9875\u9762\u7684\u6e90\u4ee3\u7801","tooltip-ca-editsource":"\u7f16\u8f91\u672c\u9875\u9762\u7684\u6e90\u4ee3\u7801","tooltip-ca-ve-edit":"\u4f7f\u7528\u53ef\u89c6\u5316\u7f16\u8f91\u5668\u7f16\u8f91\u672c\u9875","visualeditor-ca-createlocaldescriptionsource":"\u6dfb\u52a0\u672c\u5730\u8bf4\u660e\u6e90\u4ee3\u7801","visualeditor-ca-createsource":"\u521b\u5efa\u6e90\u4ee3\u7801","visualeditor-ca-editlocaldescriptionsource":"\u7f16\u8f91\u672c\u5730\u8bf4\u660e\u6765\u6e90","visualeditor-ca-editsource":"\u7f16\u8f91\u6e90\u4ee3\u7801","visualeditor-ca-editsource-section":"\u7f16\u8f91\u6e90\u4ee3\u7801"},{});mw.loader.implement("jquery.accessKeyLabel",function($,jQuery){(function($,mw){var cachedAccessKeyPrefix,useTestPrefix=false,labelable='button, input, textarea, keygen, meter, output, progress, select';function getAccessKeyPrefix(ua){
if(!ua&&cachedAccessKeyPrefix){return cachedAccessKeyPrefix;}var profile=$.client.profile(ua),accessKeyPrefix='alt-';if(profile.name==='opera'){accessKeyPrefix='shift-esc-';}else if(profile.name==='chrome'){accessKeyPrefix=(profile.platform==='mac'?'ctrl-option-':'alt-shift-');}else if(profile.platform!=='win'&&profile.name==='safari'&&profile.layoutVersion>526){accessKeyPrefix='ctrl-alt-';}else if(!(profile.platform==='win'&&profile.name==='safari')&&(profile.name==='safari'||profile.platform==='mac'||profile.name==='konqueror')){accessKeyPrefix='ctrl-';}else if((profile.name==='firefox'||profile.name==='iceweasel')&&profile.versionBase>'1'){accessKeyPrefix='alt-shift-';}if(!ua){cachedAccessKeyPrefix=accessKeyPrefix;}return accessKeyPrefix;}function getAccessKeyLabel(element){if(!element.accessKey){return'';}if(!useTestPrefix&&element.accessKeyLabel){return element.accessKeyLabel;}return(useTestPrefix?'test-':getAccessKeyPrefix())+element.accessKey;}function updateTooltipOnElement(
element,titleElement){var array=(mw.msg('word-separator')+mw.msg('brackets')).split('$1'),regexp=new RegExp($.map(array,$.escapeRE).join('.*?')+'$'),oldTitle=titleElement.title,rawTitle=oldTitle.replace(regexp,''),newTitle=rawTitle,accessKeyLabel=getAccessKeyLabel(element);if(!oldTitle){return;}if(accessKeyLabel){newTitle+=mw.msg('word-separator')+mw.msg('brackets',accessKeyLabel);}if(oldTitle!==newTitle){titleElement.title=newTitle;}}function updateTooltip(element){var id,$element,$label,$labelParent;updateTooltipOnElement(element,element);$element=$(element);if($element.is(labelable)){id=element.id.replace(/"/g,'\\"');if(id){$label=$('label[for="'+id+'"]');if($label.length===1){updateTooltipOnElement(element,$label[0]);}}$labelParent=$element.parents('label');if($labelParent.length===1){updateTooltipOnElement(element,$labelParent[0]);}}}$.fn.updateTooltipAccessKeys=function(){return this.each(function(){updateTooltip(this);});};$.fn.updateTooltipAccessKeys.getAccessKeyPrefix=
getAccessKeyPrefix;$.fn.updateTooltipAccessKeys.setTestMode=function(mode){useTestPrefix=mode;};}(jQuery,mediaWiki));},{},{"brackets":"[$1]","word-separator":""},{});mw.loader.implement("mediawiki.language",function($,jQuery){(function(mw,$){$.extend(mw.language,{procPLURAL:function(template){if(template.title&&template.parameters&&mw.language.convertPlural){if(template.parameters.length===0){return'';}var count=mw.language.convertNumber(template.title,true);return mw.language.convertPlural(parseInt(count,10),template.parameters);}if(template.parameters[0]){return template.parameters[0];}return'';},convertPlural:function(count,forms,explicitPluralForms){var pluralRules,pluralFormIndex=0;if(explicitPluralForms&&explicitPluralForms[count]){return explicitPluralForms[count];}if(!forms||forms.length===0){return'';}pluralRules=mw.language.getData(mw.config.get('wgUserLanguage'),'pluralRules');if(!pluralRules){return(count===1)?forms[0]:forms[1];}pluralFormIndex=mw.cldr.getPluralForm(count,
pluralRules);pluralFormIndex=Math.min(pluralFormIndex,forms.length-1);return forms[pluralFormIndex];},preConvertPlural:function(forms,count){while(forms.length<count){forms.push(forms[forms.length-1]);}return forms;},gender:function(gender,forms){if(!forms||forms.length===0){return'';}forms=mw.language.preConvertPlural(forms,2);if(gender==='male'){return forms[0];}if(gender==='female'){return forms[1];}return(forms.length===3)?forms[2]:forms[0];},convertGrammar:function(word,form){var grammarForms=mw.language.getData(mw.config.get('wgUserLanguage'),'grammarForms');if(grammarForms&&grammarForms[form]){return grammarForms[form][word]||word;}return word;},listToText:function(list){var text='',i=0;for(;i<list.length;i++){text+=list[i];if(list.length-2===i){text+=mw.msg('and')+mw.msg('word-separator');}else if(list.length-1!==i){text+=mw.msg('comma-separator');}}return text;}});}(mediaWiki,jQuery));(function(mw,$){function pad(text,size,ch,end){if(!ch){ch='0';}var out=String(text),padStr=
replicate(ch,Math.ceil((size-out.length)/ch.length));return end?out+padStr:padStr+out;}function replicate(str,num){if(num<=0||!str){return'';}var buf=[];while(num--){buf.push(str);}return buf.join('');}function commafyNumber(value,pattern,options){options=options||{group:',',decimal:'.'};if(isNaN(value)){return value;}var padLength,patternDigits,index,whole,off,remainder,patternParts=pattern.split('.'),maxPlaces=(patternParts[1]||[]).length,valueParts=String(Math.abs(value)).split('.'),fractional=valueParts[1]||'',groupSize=0,groupSize2=0,pieces=[];if(patternParts[1]){padLength=(patternParts[1]&&patternParts[1].lastIndexOf('0')+1);if(padLength>fractional.length){valueParts[1]=pad(fractional,padLength,'0',true);}if(maxPlaces<fractional.length){valueParts[1]=fractional.slice(0,maxPlaces);}}else{if(valueParts[1]){valueParts.pop();}}patternDigits=patternParts[0].replace(',','');padLength=patternDigits.indexOf('0');if(padLength!==-1){padLength=patternDigits.length-padLength;if(padLength>
valueParts[0].length){valueParts[0]=pad(valueParts[0],padLength);}if(patternDigits.indexOf('#')===-1){valueParts[0]=valueParts[0].slice(valueParts[0].length-padLength);}}index=patternParts[0].lastIndexOf(',');if(index!==-1){groupSize=patternParts[0].length-index-1;remainder=patternParts[0].slice(0,index);index=remainder.lastIndexOf(',');if(index!==-1){groupSize2=remainder.length-index-1;}}for(whole=valueParts[0];whole;){off=groupSize?whole.length-groupSize:0;pieces.push((off>0)?whole.slice(off):whole);whole=(off>0)?whole.slice(0,off):'';if(groupSize2){groupSize=groupSize2;groupSize2=null;}}valueParts[0]=pieces.reverse().join(options.group);return valueParts.join(options.decimal);}$.extend(mw.language,{convertNumber:function(num,integer){var i,tmp,transformTable,numberString,convertedNumber,pattern;pattern=mw.language.getData(mw.config.get('wgUserLanguage'),'digitGroupingPattern')||'#,##0.###';transformTable=mw.language.getDigitTransformTable();if(!transformTable){return num;}if(integer
){if(parseInt(num,10)===num){return num;}tmp=[];for(i in transformTable){tmp[transformTable[i]]=i;}transformTable=tmp;numberString=num+'';}else{numberString=mw.language.commafy(num,pattern);}convertedNumber='';for(i=0;i<numberString.length;i++){if(transformTable[numberString[i]]){convertedNumber+=transformTable[numberString[i]];}else{convertedNumber+=numberString[i];}}return integer?parseInt(convertedNumber,10):convertedNumber;},getDigitTransformTable:function(){return mw.language.getData(mw.config.get('wgUserLanguage'),'digitTransformTable')||[];},getSeparatorTransformTable:function(){return mw.language.getData(mw.config.get('wgUserLanguage'),'separatorTransformTable')||[];},commafy:function(value,pattern){var numberPattern,transformTable=mw.language.getSeparatorTransformTable(),group=transformTable[',']||',',numberPatternRE=/[#0,]*[#0](?:\.0*#*)?/,decimal=transformTable['.']||'.',patternList=pattern.split(';'),positivePattern=patternList[0];pattern=patternList[(value<0)?1:0]||('-'+
positivePattern);numberPattern=positivePattern.match(numberPatternRE);if(!numberPattern){throw new Error('unable to find a number expression in pattern: '+pattern);}return pattern.replace(numberPatternRE,commafyNumber(value,numberPattern[0],{decimal:decimal,group:group}));}});}(mediaWiki,jQuery));(function(mw,$){$.extend(mw.language,{getFallbackLanguages:function(){return mw.language.getData(mw.config.get('wgUserLanguage'),'fallbackLanguages')||[];},getFallbackLanguageChain:function(){return[mw.config.get('wgUserLanguage')].concat(mw.language.getFallbackLanguages());}});}(mediaWiki,jQuery));},{},{"and":"\u548c","comma-separator":"\u3001","word-separator":""},{});mw.loader.implement("mediawiki.legacy.ajax",function($,jQuery){(function(mw){function debug(text){if(!window.sajax_debug_mode){return false;}var b,m,e=document.getElementById('sajax_debug');if(!e){e=document.createElement('p');e.className='sajax_debug';e.id='sajax_debug';b=document.getElementsByTagName('body')[0];if(b.
firstChild){b.insertBefore(e,b.firstChild);}else{b.appendChild(e);}}m=document.createElement('div');m.appendChild(document.createTextNode(text));e.appendChild(m);return true;}function createXhr(){debug('sajax_init_object() called..');var a;try{a=new XMLHttpRequest();}catch(xhrE){try{a=new window.ActiveXObject('Msxml2.XMLHTTP');}catch(msXmlE){try{a=new window.ActiveXObject('Microsoft.XMLHTTP');}catch(msXhrE){a=null;}}}if(!a){debug('Could not create connection object.');}return a;}function doAjaxRequest(func_name,args,target){var i,x,uri,post_data;uri=mw.util.wikiScript()+'?action=ajax';if(window.sajax_request_type==='GET'){if(uri.indexOf('?')===-1){uri=uri+'?rs='+encodeURIComponent(func_name);}else{uri=uri+'&rs='+encodeURIComponent(func_name);}for(i=0;i<args.length;i++){uri=uri+'&rsargs[]='+encodeURIComponent(args[i]);}post_data=null;}else{post_data='rs='+encodeURIComponent(func_name);for(i=0;i<args.length;i++){post_data=post_data+'&rsargs[]='+encodeURIComponent(args[i]);}}x=createXhr()
;if(!x){alert('AJAX not supported');return false;}try{x.open(window.sajax_request_type,uri,true);}catch(e){if(location.hostname==='localhost'){alert('Your browser blocks XMLHttpRequest to "localhost", try using a real hostname for development/testing.');}throw e;}if(window.sajax_request_type==='POST'){x.setRequestHeader('Method','POST '+uri+' HTTP/1.1');x.setRequestHeader('Content-Type','application/x-www-form-urlencoded');}x.setRequestHeader('Pragma','cache=yes');x.setRequestHeader('Cache-Control','no-transform');x.onreadystatechange=function(){if(x.readyState!==4){return;}debug('received ('+x.status+' '+x.statusText+') '+x.responseText);if(typeof target==='function'){target(x);}else if(typeof target==='object'){if(target.tagName==='INPUT'){if(x.status===200){target.value=x.responseText;}}else{if(x.status===200){target.innerHTML=x.responseText;}else{target.innerHTML='<div class="error">Error: '+x.status+' '+x.statusText+' ('+x.responseText+')</div>';}}}else{alert(
'Bad target for sajax_do_call: not a function or object: '+target);}};debug(func_name+' uri = '+uri+' / post = '+post_data);x.send(post_data);debug(func_name+' waiting..');return true;}function wfSupportsAjax(){var request=createXhr(),supportsAjax=request?true:false;request=undefined;return supportsAjax;}var deprecationNotice='Sajax is deprecated, use jQuery.ajax or mediawiki.api instead.';mw.log.deprecate(window,'sajax_debug_mode',false,deprecationNotice);mw.log.deprecate(window,'sajax_request_type','GET',deprecationNotice);mw.log.deprecate(window,'sajax_debug',debug,deprecationNotice);mw.log.deprecate(window,'sajax_init_object',createXhr,deprecationNotice);mw.log.deprecate(window,'sajax_do_call',doAjaxRequest,deprecationNotice);mw.log.deprecate(window,'wfSupportsAjax',wfSupportsAjax,deprecationNotice);}(mediaWiki));},{},{},{});
/* cache key: zhwiki:resourceloader:filter:minify-js:7:8a3913eaeaedd8ccd24f807b3ff02f32 */