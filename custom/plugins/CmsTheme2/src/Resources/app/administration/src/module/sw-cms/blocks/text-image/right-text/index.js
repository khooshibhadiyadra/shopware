import './component';
import './preview';

Shopware.Service('cmsService').registerCmsBlock({
    name:'right-text',
    label:'sw-cms.blocks.textImage.rightText.label',
    category:'text-image',
    component:'sw-cms-block-right-text',
    previewComponent:'sw-cms-preview-right-text',
    defaultConfig:{
        marginBottom:'20px',
        marginTop:'20px',
        marginLeft:'20px',
        marginRight:'20px',
        sizingMode:'boxed',
    },
    slots:{
        left:{
            type:'image',
            default:{
                config:{
                    displayMode:{source:'static',value:'cover'},
                },
                data:{
                    media:{
                        url:'/administration/static/img/cms/preview-camera_large.jpg',
                    },
                },
            },
        },
        'left-text':{
            type:'text',
            default:{
                config:{
                    content:{
                        source:'static',
                        value:`
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>`
                            .trim(),

                    },
                },
            },

        },
        center:{
            type:'image',
            default:{
                config:{
                    displayMode:{source:'static',value:'cover'},
                },
                data:{
                    media:{
                        url:'/administration/static/img/cms/preview-plant_large.jpg',
                    },
                },
            },
        },
        right:{
            type:'text',
            default:{
                config:{
                    content:{
                        source:'static',
                        value:`
                        <h2 style="text-align: center;">Lorem ipsum dolor sit amet</h2>
                        <p style="text-align: center;">Lorem ipsum dolor sit amet</p>  
                        `.trim(),
                    },
                },
            },
        },
    },
});