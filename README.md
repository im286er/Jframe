# Welcome to use the Jframe®supjos v1.0.1 #
	
	Josin 774542602@qq.com Or www.supjos.cn
    It were easy to expand the framework named Jframe

# Develop Environments:

Based on ***Debian Linux 8.8*** Xfce desktop

The **OS Environment** is：

- **Linux** (***Debian GNU/Linux 8.8 Xfce 4.10***)
- Linux ***Apache 2.4.25*** from source code compile and optimization
- Linux ***PHP 7.1.5*** from source code compilation
- Linux ***MySQL5.7.15*** from DEB package installation
- Linux ***Nginx 1.11.8*** from source code compilation
- IDE: ***Netbeans 8.2***

# Basic Use:

***1***：The ***hierachy*** of the the ***Jframe***
    
    config----------------------------------- The config directory[配置目录]
        |----bootstrap.php ------------------ The bootstrap config [引导配置文件]
        |----web.php       ------------------ The web config file [主配置文件]
    controller------------------------------- The Default Controller[无模块控制器目录]
    core------------------------------------- The core Directory [Jframe核心框架目录]
        |--base            ------------------ The core files [Jframe框架核心目录]
        |--behavior        ------------------ Behavior files [Jframe行为库]
        |--helpers         ------------------ Helper tools [Jframe帮助工具库]
        |--di              ------------------ The DI files [Jframe依赖注入库IOC]
        |--tools           ------------------ Collection tools [Jframe 的工具库]
    modules---------------------------------- The modules place[模块目录]
        |--admin [实例：模块admin]------------ The module named admin [admin模块]
        |--fronted·[实例：模块fronted]-------- The module named fronted [fronted模块]
    vendor----------------------------------- The third vendor librarys [第三方组建库]
    views------------------------------------ The view files [视图存放目录]
    web-------------------------------------- The web entrance file [index.php 入口文件]

***2***：After you deployed into you system, do below：
    
    1: Start your favorite browser:
    
    2：Enter the url : 
		http://path_url_to_jframe/index.php 
		or
		http://path_url_to_jframe/index.php/site/index to the no module controller

    3：Enter the url :
		http://path_url_to_jframe/index.php/b/site/index To enter the module b's controller

***3***: ***Features***
    
- 1): ***IOC library***
	
	When you want to use the ***IOC*** of the ***Jframe*** project。
	
	Follow the steps:

	a): The base class must declared as below [Remember not to declare the contructor function, with parameter]:

            class Base
            {
                // Some properties declared
                private $options;
                private $version;
            }

	b): The derived class depend on the class Base, so declared as follow:

            class Pants
            {
                /**
                 * @var Base $base
                 */
                private $base;
                public function __constructor(Base $base){
                    $this->base = $base;
                }
                // Some other properties you can declare as the class Base:
                private $class;
                private $size;
            }

            class BigPants
            {
                /**
                 * @var Pants $pants
                 */
                private $pants;
                public function __constructor(Pants $pants){
                    $this->pants = $pants;
                }
                // Some other properties you can declare as the class Base:
                private $class;
                private $size;
            }

	c)：After doing the class create process, You can let the class-object process to the Jframe Reflex tool:
    
        use Jframe\di\Reflex;

        $container = new Reflex();

        // The Second parameter to pass some value which the class need, if you want to get it with the Object
        $container->set('Base', ['options'=>['optionA'=>'optionA', 'optionB'=>'optionB'], 'version'=>'2.1.2']);
        $container->set('Pants', ['class'=>'Black', 'size'=>'170/85A']);
        $container->set('BigPants', ['class'=>'White', 'size'=>'185/85B']);

        // After doing the before steps, if you want the Object of the class named 'Pants', You can do the step below:
        $pants = $container->get('Pants');

        // Also to get the Object of the class 'BigPants':
        $bigPants = $container->get('BigPants');

- 2)：The global variable named : ***Jframe::$app***.
        
        You can use the Jframe::$app to get the Object with the class name configured in the web.php file, such as the default
        Request components:
            $request = Jframe::$app->request;
        Or the Response Object:
            $response = Jframe::$app->response;

- 3)：The ***Access-Control***:
        
	Each controller can implement the behaviors function to get the feature:

		public function behaviors()
		{
		    return [
		        'verbs' => [
		            'actions' => [
		                'index' => ['post', 'get']
		            ]
		        ],
		        'access' => [
		            'class' => AccessFilter::className(),
		            'only' => ['index', 'say'],
		            'rules' => [
		                    [
		                    'allow' => true,
		                    'roles' => '?',
		                	// 'verbs' => ['get']
		                ],
		                    [
		                    'allow' => true,
		                    'verbs' => ['get']
		                ]
		            ]
		        ]
		    ];
		}
	Each controller can control the process wheather to deal with it or not.
	
	In the behaviors the return array can have two element: one is ***verbs***, the other is ***access***, The ***verbs*** can contain the ***actions*** which points that which action must be processed with the request method: such as GET. POST . HEAD etc. or not.
	
	For example:
		     
		'verbs' => [
		    'actions' => [
		        'index' => ['post', 'get']
		    ]
		]
	Means that the action named 'index' : ***actionIndex()*** will only accept the request method ***POST & GET***, The other request method will reject.

    The ***access*** element:
		
		'access' => [
		    'class' => AccessFilter::className(),
		    'only' => ['index', 'say'],
		    'rules' => [
		        [
		            'allow' => true,
		            'roles' => '?',
		            // 'verbs' => ['get']
		        ],
		        [
		            'allow' => true,
		            'verbs' => ['get']
		        ]
		    ]
		]
		
	Must contains the class elment which tells us the class named ***AccessFilter*** to deal the process.

	The rules are:
	
	Only deal with the **actionIndex() & actionSay()**, Each rule in the rules array define the rule, the example before means that when the user is guest[not login-in] will ***allow*** to access the ***actionIndex & actionSay***, The second means that Only the request method ***GET*** allowed to access the ***actionIndex() & actionSay()***.

***4***：Model Validator
    
   Each class derived from the Jframe\base\Model has the model validate attributes
    
   A simple example like that below:
    
    
    class UserModel extends Model
    {
        public $userName;
        public $userAge;
        public $mobile;
        public $gender;
    
        /**
         * The validate rules
         * @return array
         */
        public function rules()
        {
            return [
                [['userName', 'mobile', 'userAge', 'gender'], 'required'],
                [['mobile'], 'email', 'message' => '邮箱非法!']
            ];
        }
    
        public function attributeLabels()
        {
            return [
                'userName'=>'用户姓名',
                'mobile'=>'手机号码',
                'gender'=>'性别'
            ];
        }
    }
    
    It was extreamly similar with the Yii framework, as i used mostly yii in
    my PHP development. So learn some experience from it, and apply it in Jframe
    

***5***：***More features waiting you to join the project~~~***