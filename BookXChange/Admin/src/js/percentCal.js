
function percentCal(rating)
{
    const starPercentage = (rating / 5) * 100;
    const starPercentageRounded = Math.round(starPercentage / 10) * 10 ;
    // console.log(starPercentageRounded);
    
    document.getElementById("stars-inner").style.width = starPercentageRounded + "%";
    // console.log("here");


}