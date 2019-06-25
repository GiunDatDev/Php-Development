function programNav(){
    var userChoice = prompt("Enter the command at here ...");
    if (userChoice == "NEW"){
        return 1;
    } else if (userChoice == "PRINT"){
        return 2;
    } else if (userChoice == "CLEAR"){
        return 3;
    } else if (userChoice == "DELETE"){
        return 4;
    } else if (userChoice == "EDIT"){
        return 5;
    } else if (userChoice == "STOP"){
        return 6;
    } else {
        return 404;
    }
}

function addList(userList){
    var item = prompt("What do you want to add into the list ???");
    if (item != "" && item != NaN && item != undefined){
        if (userChoice == "DONE"){
            return -1;
        } else {
            userList.push(item);
        }
    } else {
        alert("Sorry, but I can not get what you mean !!!");
        var userChoice = confirm("Do you want to continue ???");
        if (userChoice == true){
            addList(userList);
        } else {
            return -1;
        }
    }
}

function printList(userList){
    var list = "";
    var counter = 0;
    userList.forEach(function(item) {
        list = list + counter + ". " + item + "\n";
        ++counter;  
    });
    alert("This is the to-do list that you need to get done !!!" + "\n" + list);
}

function removeItem(userList, position){
    userList.splice(position, 1);
}

function removeList(userList){
    userList.splice(0);
}

function editList(userList, position, todoPlan){
    userList.splice(position, 1, todoPlan);
}

function todoStart(){
    var userData = [];
    var cmdSts = programNav();
    while (cmdSts != -1){
        if (cmdSts == 1){
            var functionStatus = addList(userData);
            while (functionStatus != -1){
                functionStatus = addList(userData);
            } 
            cmdSts = programNav();  
        } else if (cmdSts == 2){
            printList(userData);
            cmdSts = programNav();
        } else if (cmdSts == 3){
            removeList(userData);
            alert("LIST REMOVED !!!");
            cmdSts = programNav();
        } else if (cmdSts == 4){
            printList(userData);
            var postion = Number(prompt("Which one would you like to delete ???"));
            alert("ITEM REMOVED !!!");
            removeItem(userData, postion);
            cmdSts = programNav();
        } else if (cmdSts == 5){
            printList(userData);
            var postion = Number(prompt("Which one would you like to delete ???"));
            var userString = prompt("What do you want to replace with ???");
            editList(userData, postion, userString);
            alert("LIST EDITED !!!");
            cmdSts = programNav();
        } else if (cmdSts == 6){
            alert("Thank you for using this service !!!");
            return -1;
        } else {
            alert("Please try again !!!");
            cmdSts = programNav();
        }
    }
}

todoStart();