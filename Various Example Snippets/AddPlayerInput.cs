using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using TMPro;
using System.Linq;
using UnityEngine.EventSystems;

public class AddPlayerInput : MonoBehaviour
    {

        public InputField input;
        public GameObject ScrollableContent;
        public List<string> playersList = new List<string>();
        public GameObject playerTagPrefab;
        public GameObject playerNameTag;
        public TMP_Text playerNameText;
   

        void Start() 
        {
            ES3.Save("playersList", playersList);
            input.Select();
            input.ActivateInputField();
        }

        public void StoreName()
        {

        //loop thrpough input and check theres at least one non-space character

           bool atLeastOneNotspace = false;

        for (int i = 0; i < input.text.Length; i++)
        {

            if (!(input.text[i] == ' '))
            {
                atLeastOneNotspace = true;
                break;
            }
        }


        if (atLeastOneNotspace)
            {
            input.text = input.text.Trim();

            playersList = ES3.Load<List<string>>("playersList");
            playersList.Add(input.text);
            ES3.Save("playersList", playersList);

            playerNameTag = Instantiate(playerTagPrefab);
            playerNameText = playerNameTag.GetComponentInChildren<TMP_Text>();
            playerNameText.text = playersList.Last();
            playerNameTag.transform.SetParent(ScrollableContent.transform);
            }

            input.text = "";
        }

    }
