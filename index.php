<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>SM3 Recreated</title>
    <script src="//cdn.jsdelivr.net/npm/phaser@3.11.0/dist/phaser.js"></script>
    <style type="text/css">
        body {
            margin: 0;
        }
    </style>
</head>

<body>

    <script type="text/javascript">
        var lifeCount = 3;
        var gameOver = false;
        var zoomFactor = 2;
        
        var config = {
            type: Phaser.AUTO,
            width: 256 * zoomFactor,
            height: 224 * zoomFactor,
            physics: {
                default: 'arcade',
                arcade: {
                    gravity: {
                        y: 1000
                    },
                    debug: false
                }
            },
            scene: {
                preload: preload,
                create: create,
                update: update
            }
        };
        
        var game = new Phaser.Game(config);
        
        function preload() {
            //loading PNG map file
            this.load.image('image_1-1', 'assets/map/1-1.png')

            //loading JSON file
            this.load.tilemapTiledJSON('tilemap', 'assets/map/1-1.json')

            //load music
            this.load.audio('world1_background_music', 'assets/sounds/world1.ogg');


            //load character
            this.load.spritesheet('smallmario', 'assets/characters/smallmariospritesheet.png', {
                frameWidth: 16,
                frameHeight: 16
            })
        }

        function create() {

            this.physics.world.setBounds(0, 0, 176*16, 39*16);

            // create the Tilemap
            const map = this.make.tilemap({
                key: 'tilemap'
            })
            const tileset = map.addTilesetImage('1-1', 'image_1-1')

            //add layers
            var background = map.createStaticLayer('Background', tileset)
            var oob = map.createStaticLayer('OutOfBounds', tileset)
            var ground = map.createStaticLayer('Ground', tileset)
            map.createStaticLayer('Bushes', tileset)
            var tubes = map.createStaticLayer('Tubes', tileset)
            map.createStaticLayer('Shadows', tileset)
            map.createStaticLayer('Boxes', tileset)

            //creating collision with layers of tileset
            ground.setCollisionByExclusion([-1]);
            tubes.setCollisionByExclusion([-1]);
            oob.setCollisionByExclusion([-1]);

            //load music
            backgroundMusic = this.sound.add('world1_background_music', {
                loop: true
            });
            backgroundMusic.play();

            //add the player character, gives him collision
            this.player = this.physics.add.sprite(100, 350, 'smallmario');
            this.player.setCollideWorldBounds(true);
            // player.setBounce(0.1);
            this.physics.add.collider(this.player, ground);
            this.physics.add.collider(this.player, tubes);
            this.physics.add.collider(this.player, oob, outOfBounds, null, this);

            cursors = this.input.keyboard.createCursorKeys();

            //create animations
            this.anims.create({
                key: 'left',
                frames: this.anims.generateFrameNumbers('smallmario', {
                    start: 0,
                    end: 1
                }),
                frameRate: 10,
                repeat: -1
            });

            this.anims.create({
                key: 'turn',
                frames: [{
                    key: 'smallmario',
                    frame: 2
                }],
                frameRate: 60
            });

            this.anims.create({
                key: 'right',
                frames: this.anims.generateFrameNumbers('smallmario', {
                    start: 2,
                    end: 3
                }),
                frameRate: 10,
                repeat: -1
            });

            const cam = this.cameras.main;
            cam.setViewport(0, 0, 256 * zoomFactor, 224 * zoomFactor);
            cam.zoom = zoomFactor;
            cam.startFollow(this.player, true, 0.075, 0.075);
            

        }

        function update() {
            // if (gameOver) {
            //     return;
            // }

            if (cursors.left.isDown) {
                this.player.setVelocityX(-160);
                this.player.anims.play('left', true);
            } else if (cursors.right.isDown) {
                this.player.setVelocityX(160);
                this.player.anims.play('right', true);
            } else {
                this.player.setVelocityX(0);

                this.player.anims.play('turn');
            }

            if (cursors.up.isDown && this.player.body.onFloor()) {
                this.player.setVelocityY(-350);
            }
        }

        function outOfBounds(player, oob) {
            this.physics.pause();
            player.setTint(0xff0000);
            if (lifeCount <= 0) gameOver = true;
            else lifeCount--;
        }
    </script>
</body>

</html>