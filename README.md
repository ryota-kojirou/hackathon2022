# HomeMee

## 自分のところにフォルダを持ってくる（はじめの一回のみ）
- `git clone git@github.com:nkmr-lab/6th_Hackathon2022`

## 自分のところのフォルダで編集した時
- `git add [ファイル名(例：/client/src/views/HomeView.vue)]`
- `git commit -m "(変更内容を自由にコメントする)"`
- `git push origin (branch名：自分の名前)`

## 他の人の作業を反映する
- 詳しくわからんけど多分`git pull`したらいけるんじゃないか
- `git pull origin main`

pull request

pullで変なの出たら　:q

## Project setup
```
npm install
```

## 開発したやつの動作を確認する 
```
npm run serve
```

### Compiles and minifies for production
```
npm run build
```

### Lints and fixes files
```
npm run lint
```

### Customize configuration
See [Configuration Reference](https://cli.vuejs.org/config/).
