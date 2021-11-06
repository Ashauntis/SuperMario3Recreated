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
        var config = {
            type: Phaser.AUTO,
            width: 800,
            height: 600,
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
        var ground

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
            // create the Tilemap
            const map = this.make.tilemap({
                key: 'tilemap'
            })

            // add the tileset image we are using
            const tileset = map.addTilesetImage('1-1', 'image_1-1')

            //add layers
            map.createStaticLayer('Background', tileset)
            var ground = map.createStaticLayer('Ground', tileset)
            map.createStaticLayer('Bushes', tileset)
            var tubes = map.createStaticLayer('Tubes', tileset)
            map.createStaticLayer('Shadows', tileset)
            map.createStaticLayer('Boxes', tileset)

            //ground collision: https://stackoverflow.com/questions/34214162/creating-a-collision-layer-in-phaser-using-json-and-tiled
            ground.setCollisionByExclusion([-1]);
            tubes.setCollisionByExclusion([-1]);

            //load music
            backgroundMusic = this.sound.add('world1_background_music', {
                loop: true
            });
            backgroundMusic.play();

            //add the player character, gives him collision
            player = this.physics.add.sprite(100, 350, 'smallmario');
            player.setCollideWorldBounds(true);
            player.setBounce(0.1);
            this.physics.add.collider(player, ground);
            this.physics.add.collider(player, tubes);

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
                    frame: 3
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

        }

        function update() {
            // if (gameOver) {
            //     return;
            // }

            if (cursors.left.isDown) {
                player.setVelocityX(-160);
                player.anims.play('left', true);
            } else if (cursors.right.isDown) {
                player.setVelocityX(160);
                player.anims.play('right', true);
            } else {
                player.setVelocityX(0);

                player.anims.play('turn');
            }

            if (cursors.up.isDown && player.body.onFloor())
                player.setVelocityY(-350);

        }
    </script>
</body>

</html>