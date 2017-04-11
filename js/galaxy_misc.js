function iChineseCount(word){
    return word.split(/[\u4e00-\u9a05]/).length -1;
}