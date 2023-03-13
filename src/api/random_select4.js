// サンプルデータの定義
let members = ["FVzjDUOLgCOkgNgJWvacDDweWDr2", "1U4HYZjmRaSffeYkyajrIGhGMz93", "HbeCszT7G9gvPf9xskygSYc0Czq2", "X79Ga5ZKJbaoeHLda1fJvhL15nn2", "cWxwJgklwbcLo5TNFDNMZahERrI3", "a"];

// 楽するために作った関数たち
const shuffle = ([...array]) => {
    for (let i = array.length - 1; i >= 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [array[i], array[j]] = [array[j], array[i]];
    }
    return array;
  }
function check_double(arrayData, arrayData2){
    for(let i=0; i<arrayData.length; i++){
        if(arrayData[i]==arrayData2[i]){
            return true;
        }
    }
    return false;
}

//これがメイン 
function random_select4(arrayData){
    let members_count = [];
    let result_random = {};
    let praise_count = [];

    for(let i=0; i<arrayData.length; i++){
        members_count[i] = arrayData[i];
    }
    for(let i=0; i<4; i++){
        praise_count[i] = members_count;
    }

    // どれとも被っていないこと，過去に自分が当たった人に当たらないこと
    for(let i=0; i<4; i++){
        praise_count[i] = shuffle(praise_count[i]);

        let flag = 0;

        if(i==0){
            flag=1;
        }

        // 元の状態と比較して一つでも一致していたらflag=0
        if(check_double(members_count, praise_count[i])){
            flag=0;
        }else{
            flag=1;
        }

        // 過去のシャッフル結果と比較
        if(flag==1){
            for(let n=0; n<i; n++){
                if(check_double(praise_count[i], praise_count[n])){
                    flag=0;
                    break;
                }else{
                    flag=1;
                }
            }
        }
        // console.log(check_double(members_count, praise_count[0]),  flag==0, praise_count[i]);
        while(flag==0){
            praise_count[i] = shuffle(praise_count[i]);
            if(i==0){
                flag=1;
            }
            // 元の状態と比較して一つでも一致していたらflag=0
            if(check_double(members_count, praise_count[i])){
                flag=0;
            }else{
                flag=1;
            }

            // 過去のシャッフル結果と比較
            if(flag==1){
                for(let n=0; n<i; n++){
                    if(check_double(praise_count[i], praise_count[n])){
                        flag=0;
                        break;
                    }else{
                        flag=1;
                    }
                }
            }
        }
    }
    // デバック用
    // console.log(praise_count);

    // 結果の整理をする
    for(let k=0; k<arrayData.length; k++){
        let temp =[];
        for(let v=0; v<4; v++){
            temp.push(praise_count[v][k]);
        }
        result_random[arrayData[k]] = temp;
    }

    // 結果を返す(ここはよくわからないから合うようにいじってほしい)
    console.log(result_random);
    return result_random;
}

// 試しに作った関数を動かす場所
random_select4(members);
