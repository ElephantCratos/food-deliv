Flashphoner tools.
This is a collection of bash scripts that can be helpful in debugging.

dump_sessions.sh
	Main purpose is to gather pcap dumps of traffic related to Flashphoner from every interface.
	This script has 4 arguments:
	1) RF -  path to directory which will contain pcap files
	2) INTERVAL -  timeout for pcap files rotation in seconds
	3) MAX_SIZE - maximum size of RF directory in megabytes. When maximum size exceeded script will delete oldest pcap file.
	4) MODE:
		SIGNALLING - gather RTMFP and SIP traffic
		MEDIA - gather RTP traffic
		SIGNALLING_MEDIA - gather RTMFP, SIP and RTP traffic

	Starting script.
	By default script will start with such configuration:
	RF = "/usr/local/FlashphonerWebCallServer/logs/dumps"
	INTERVAL = "600"
	MAX_SIZE = "256"
	MODE = "SIGNALLING_MEDIA"

	You can change default values when starting it. For example change MAX_SIZE to 2Gb:
	./dump_sessions.sh "" "" 2048