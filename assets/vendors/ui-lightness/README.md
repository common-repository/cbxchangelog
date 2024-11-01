# jQuery UI Flat UI

Requires:

- jQuery UI: v1.11.4
- jQuery: v1.6+

This is aimed at being a faithful reproduction of the [Flat UI][4] in jQuery UI, just using the theme roller.

Notes:

With the theme roller, there are constant trade offs, so you can blame a lot of the inconsistencies on that.

- Font defaults to the correct Lato font if you have it installed
- Font is switched from Bold to Normal to remove some inconsistencies (menu item hovers) and match the buttons better
- The corner radius now matches at 6px
- The default/hover/active styles match the button rather than header menu
- Fix the hidden header icon colours (i.e. Date picker prev/next)
- Match the highlight / warning messages to the [Pro version][3]

## Original [Turquoise / Wet asphalt theme roller][1]

- Uses the Inverse button as default
- Match the modal colours and shadow to the [Pro version][3]

## Monochrome [Turquoise / Green sea theme roller][2]

- Uses the Primary button as default
- Matches the colours for menus and date pickers
- Incorrect colours for modal

This matches more of the default colours used. The hover colour is pulled from the Flat UI CSS. The modal shadow colour is a guess.

## Monochrome [Wet asphalt / Midnight blue theme roller][5]

- Uses the Inverse button as default

This was a version I was looking for, that uses the more sombre dark blue colours throughout. But is now significantly different from the original Flat UI.

  [1]: http://jqueryui.com/themeroller/#!zThemeParams=5d000001002306000000000000003d8888d844329a8dfe02723de3e570162214ca12b8cb8413b6f9589fa4e2b1a2db6d1bf51a21a6d3eb8745ba8a45845887cdd2c81bb11e5b1e218a622ba1e2b65ef484aa737e7f97cb9436fbfff675c854a94e12dbd1d177e2915524c2a1c915f642815e681f8bfde06ebbed23c6d840e980961719941a0d63371ad9525b7fd72ab56307a2ce7f67ae7cbc813e94f156471a9a0f12377de053405ce36cac0439cfe24ee1ff80538f81895720dd612ee0879cfa835a7361f471bb7d3eecb592ce3216e96caf4171281380ac0dc27753c39ca82ab53c570e775afa6127b627c4a7b1ffd67fb416911db041e84d5dc7d0dcb84876aeb192892096c9839e39d4dd96b53582789551d6c9a4dfeaff8557b86e884cd485a878c9e66375b58e483b92a8618cd1cfdfd06e52017d260bb4b456c8c91a2c5912a0ed2e89c61863b8e484927bb00c2be9cd644fe4313ad8475a5ea87c6214d45f3304c0119e9cedf96af83e54a30649d6abcfdea4ca05bedd307220f0f32b5784bd69419ddc9c5a0438f0a0d88d4dca9b926796ee6af98593c50f9ebf11f273171d2a4ecdcf5f1681b8f5cd4bfb13a612cf578a508616bde7470eb5fe9f7466377ece42f9b53f3e97744f85a1ec1be3e81c4f52806f6d301155ff62e6b700
  [2]: http://jqueryui.com/themeroller/#!zThemeParams=5d000001002006000000000000003d8888d844329a8dfe02723de3e570162214ca12b8cb8413b6f9589fa4e2b1a2db6d1bf51a21a6d3eb8745ba8a45845887cdd2c81bb11e5b1e218a622ba1e2b65ef484aa737e7f97cb9436fbfff675c854a706b2dbd1d177e2915524c2a1c915f642815e5d0f064f717ee5f11c505d7f120d2531fbe074d59e8cc12fe82a7efbd4a4f6f31e0621e0302ac940447be494b321b5f6803dffc44ac82b45635f1814d37402726ed8cc8c83751c009d4a98002a0ee6136fd15e195fef7511fcf09c39013952bf960c25897103d04894be4e1745c0a6e7c51859bbf79a466a3314f6db7737208b7a3f3f1ea1b96a8ce4aec1d141637cc8a454e47ab31648a24f512106277c6cc237b51399dea878a7313a190fede2d1893b2ec6d520f4979c7ece57c81711512ea8a09582690db93f6129015ee76dfa14a532ce28723cf52f0495de1a7ec72d7c2bf380ec052ba1aa60d0ad98085a89498fb84ebd31de59a6faac1ec37433f85d937d23ef4dda1b59195f3f4fd6b0b2103c242b1fdff12a201d8ba3bd84e8b27b0e00d385ba944390d5a11b32d5b4cd418c5110edb8c4dc280dcc9509fd4ad1c77fc9c77a86a68cc204ecf701c63d127cafd363437dcad7a97daeaa68ec0835724ea7f595539cff03c9ee24b9afcd8bfffc4d9580
  [3]: http://designmodo.com/flat
  [4]: http://designmodo.github.io/Flat-UI/
  [5]: http://jqueryui.com/themeroller/#!zThemeParams=5d000001002306000000000000003d8888d844329a8dfe02723de3e570162214ca12b8cb8413b6f9589fa4e2b1a2db6d1bf51a21a6d3eb8745ba8a45845887cdd2c82e81fed11c48a096905bb34f0f172e90a8dbd00828224e4f67d78232ab571cb57122e083af44d1f4ccc98bb5448ed23259452f7007ac8344d9dc37460dfb92eb9644ca68e1a8c4a36e874a8c1766e74aa9d6cd7ae2cd094469debeb6134ab317f3c80e0f5136ae0819ca838f5ef1b7452fa9c37fdcf14de580ab2f72945f0be874644f9686da0b4a9a1b741a10005c8afd94b694f376b10f17f215a848f4b96cf9a7593b75236319cc0d86754139c680ee6e0df5d3262ac946b5110d5d57369f138b66bafd66ef7f96484772738f8161152dd945f142243ead135d5b0f14a3597b3b69ae3f697adad42bfba46f2712b055aa236b929a22957e4ba70ae0a4a50afcf10467479cb88c3ff3182423f89ea71707f44c2b05e4e093aa65fca2611c0cbc9a4bbe79d56f37ba7d3ac765a6becc57e79a79e6582428a0c012b970f1e5655527299f19e00f21be4f3f640cd56c820302d4006f4f1c0b8d79b0cbbd1c72bf308fc336d114334f1c345e2c938e7706e1e29be0d0660d488714eec8e8f4f80f45f7b5d1249c3f777c2041d666a119344b197e604b4b5fffe0f01e7d

